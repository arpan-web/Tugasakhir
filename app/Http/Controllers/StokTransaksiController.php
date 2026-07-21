<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\StokTransaksi;
use App\Models\Obat;
use App\Models\CatatanHarianObat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StokTransaksiController extends Controller
{
    public function index()
    {
        $transaksis = StokTransaksi::with(['obat', 'user'])->orderBy('tanggal_transaksi', 'desc')->paginate(15);
        return view('stok_transaksi.index', compact('transaksis'));
    }

    public function create()
    {
        $obats = Obat::orderBy('nama_obat')->get();
        return view('stok_transaksi.create', compact('obats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_obat' => 'required|exists:obats,id_obat',
            'jenis_transaksi' => 'required|in:masuk,keluar',
            'jumlah' => 'required|integer|min:1',
            'tanggal_transaksi' => 'required|date',
            'keterangan' => 'nullable|string'
        ]);

        $obat = Obat::findOrFail($request->id_obat);

        if ($request->jenis_transaksi == 'keluar' && $obat->stok_tersedia < $request->jumlah) {
            return back()->withInput()->withErrors(['error' => "Stok tidak mencukupi. Stok {$obat->nama_obat} yang tersedia hanya {$obat->stok_tersedia}."]);
        }

        DB::beginTransaction();
        try {
            // Catat transaksi stok
            StokTransaksi::create([
                'id_obat' => $request->id_obat,
                'jenis_transaksi' => $request->jenis_transaksi,
                'jumlah' => $request->jumlah,
                'tanggal_transaksi' => $request->tanggal_transaksi,
                'keterangan' => $request->keterangan,
                'id_user' => Auth::id()
            ]);

            // Hitung Stok Awal hari ini (Bisa dipakai untuk laporan catatan harian obat)
            $catatanHarian = CatatanHarianObat::firstOrCreate(
                ['tanggal_catatan' => $request->tanggal_transaksi, 'id_obat' => $obat->id_obat],
                ['stok_awal' => $obat->stok_tersedia, 'stok_akhir' => $obat->stok_tersedia]
            );

            // Update Stok Master & Catatan Harian
            if ($request->jenis_transaksi == 'masuk') {
                $obat->increment('stok_tersedia', $request->jumlah);
                $catatanHarian->increment('jumlah_masuk', $request->jumlah);
            } else {
                $obat->decrement('stok_tersedia', $request->jumlah);
                $catatanHarian->increment('jumlah_keluar', $request->jumlah);
            }
            
            // Re-sync stok akhir
            $catatanHarian->update(['stok_akhir' => $obat->stok_tersedia]);

            DB::commit();
            return redirect()->route('stok_transaksi.index')->with('success', 'Transaksi Stok berhasil dicatat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Gagal menyimpan transaksi: ' . $e->getMessage()]);
        }
    }

    public function show(string $id)
    {
        // ...
    }

    public function edit(string $id)
    {
        // Data transaksi seharusnya tidak diubah sembarangan demi integritas. Jika salah, dihapus lalu input lagi.
        return redirect()->route('stok_transaksi.index')->with('warning', 'Transaksi logistik tidak dapat diubah (immutable). Silakan hapus transaksi lalu rekam ulang jika ada kesalahan.');
    }

    public function update(Request $request, string $id)
    {
        // ...
    }

    public function destroy(string $id)
    {
        $transaksi = StokTransaksi::findOrFail($id);
        $obat = Obat::findOrFail($transaksi->id_obat);
        
        // Logical check: Jika membatalkan 'masuk', apakah stok cukup untuk dikurangi?
        if ($transaksi->jenis_transaksi == 'masuk' && $obat->stok_tersedia < $transaksi->jumlah) {
            return back()->withErrors(['error' => 'Gagal membatalkan transaksi Masuk karena stok obat saat ini sudah terpakai dan tidak mencukupi untuk direstore.']);
        }

        DB::beginTransaction();
        try {
            // Restore Stok Master
            if ($transaksi->jenis_transaksi == 'masuk') {
                $obat->decrement('stok_tersedia', $transaksi->jumlah);
            } else {
                // membatalkan 'keluar' berarti menambah stok
                $obat->increment('stok_tersedia', $transaksi->jumlah);
            }

            // Restore Catatan Harian opsional (kompleksitas diredam sementara untuk pembatalan agar tidak mengubah record lewat)
            
            $transaksi->delete();

            DB::commit();
            return redirect()->route('stok_transaksi.index')->with('success', 'Transaksi Stok berhasil dibatalkan dan stok telah dikembalikan ke kondisi sebelumnya.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal membatalkan transaksi: ' . $e->getMessage()]);
        }
    }
}
