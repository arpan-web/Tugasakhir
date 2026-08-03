<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\StokTransaksi;
use App\Models\Obat;
use App\Models\CatatanHarianObat;
use App\Models\Notifikasi;
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
            'keterangan' => 'nullable|string',
            'tanggal_kadaluarsa' => 'nullable|date',
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
                'sisa_stok' => $request->jenis_transaksi === 'masuk' ? $request->jumlah : 0,
                'tanggal_transaksi' => $request->tanggal_transaksi,
                'keterangan' => $request->keterangan,
                'tanggal_kadaluarsa' => $request->jenis_transaksi === 'masuk' ? $request->tanggal_kadaluarsa : null,
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

                // FEFO: Kurangi sisa_stok pada batch transaksi masuk
                $remainingToDeduct = $request->jumlah;
                $batches = StokTransaksi::where('id_obat', $request->id_obat)
                    ->where('jenis_transaksi', 'masuk')
                    ->where('sisa_stok', '>', 0)
                    ->orderByRaw('CASE WHEN tanggal_kadaluarsa IS NULL THEN 1 ELSE 0 END, tanggal_kadaluarsa ASC, id_stok_transaksi ASC')
                    ->get();

                foreach ($batches as $batch) {
                    if ($remainingToDeduct <= 0) break;
                    if ($batch->sisa_stok >= $remainingToDeduct) {
                        $batch->decrement('sisa_stok', $remainingToDeduct);
                        $remainingToDeduct = 0;
                    } else {
                        $remainingToDeduct -= $batch->sisa_stok;
                        $batch->update(['sisa_stok' => 0]);
                    }
                }
            }

            // Re-sync stok akhir
            $catatanHarian->update(['stok_akhir' => $obat->stok_tersedia]);

            // Cek stok setelah transaksi
            $obat->refresh();
            if ($request->jenis_transaksi === 'masuk' && $obat->stok_tersedia > $obat->stok_minimal) {
                // Hapus notifikasi stok kritis jika stok sudah normal kembali
                Notifikasi::where('tipe', 'stok')
                    ->where('pesan', 'like', "%'{$obat->nama_obat}'%")
                    ->delete();
            }

            if ($obat->stok_tersedia <= $obat->stok_minimal) {
                $pesanNotif = "Stok obat '{$obat->nama_obat}' menipis! Sisa stok: {$obat->stok_tersedia} {$obat->satuan} (batas minimal: {$obat->stok_minimal}).";
                $sudahAda = Notifikasi::where('tipe', 'stok')
                    ->where('status', 'belum_dibaca')
                    ->where('pesan', 'like', "%'{$obat->nama_obat}'%")
                    ->exists();
                if (!$sudahAda) {
                    Notifikasi::create([
                        'pesan' => $pesanNotif,
                        'tipe' => 'stok',
                        'status' => 'belum_dibaca',
                    ]);
                }
            }

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

        if ($transaksi->jenis_transaksi == 'masuk' && $obat->stok_tersedia < $transaksi->jumlah) {
            return back()->withErrors(['error' => 'Gagal membatalkan transaksi Masuk karena stok obat saat ini sudah terpakai dan tidak mencukupi untuk direstore.']);
        }

        DB::beginTransaction();
        try {
            if ($transaksi->jenis_transaksi == 'masuk') {
                $obat->decrement('stok_tersedia', $transaksi->jumlah);
            } else {
                $obat->increment('stok_tersedia', $transaksi->jumlah);
            }

            $transaksi->delete();

            DB::commit();
            return redirect()->route('stok_transaksi.index')->with('success', 'Transaksi Stok berhasil dibatalkan dan stok telah dikembalikan ke kondisi sebelumnya.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal membatalkan transaksi: ' . $e->getMessage()]);
        }
    }

    /**
     * Memusnahkan sisa stok dari batch yang telah kadaluarsa.
     */
    public function musnahkan(string $id)
    {
        $batch = StokTransaksi::findOrFail($id);
        if ($batch->jenis_transaksi !== 'masuk' || $batch->sisa_stok <= 0) {
            return back()->with('warning', 'Batch ini tidak memiliki sisa stok untuk dimusnahkan.');
        }

        $jumlahDisposed = $batch->sisa_stok;
        $obat = Obat::findOrFail($batch->id_obat);

        DB::beginTransaction();
        try {
            // 1. Buat transaksi keluar untuk pemusnahan
            StokTransaksi::create([
                'id_obat' => $batch->id_obat,
                'jenis_transaksi' => 'keluar',
                'jumlah' => $jumlahDisposed,
                'sisa_stok' => 0,
                'tanggal_transaksi' => now(),
                'keterangan' => "Pemusnahan stok kadaluarsa (Batch Trx #{$batch->id_stok_transaksi}, Expired: " . ($batch->tanggal_kadaluarsa ? date('d/m/Y', strtotime($batch->tanggal_kadaluarsa)) : '-') . ")",
                'id_user' => Auth::id()
            ]);

            // 2. Set sisa_stok batch ini jadi 0
            $batch->update(['sisa_stok' => 0]);

            // 3. Kurangi stok_tersedia di master obat
            if ($obat->stok_tersedia >= $jumlahDisposed) {
                $obat->decrement('stok_tersedia', $jumlahDisposed);
            } else {
                $obat->update(['stok_tersedia' => 0]);
            }

            DB::commit();
            return back()->with('success', "Stok kadaluarsa {$obat->nama_obat} sebanyak {$jumlahDisposed} {$obat->satuan} berhasil dimusnahkan.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal memusnahkan stok kadaluarsa: ' . $e->getMessage()]);
        }
    }
}
