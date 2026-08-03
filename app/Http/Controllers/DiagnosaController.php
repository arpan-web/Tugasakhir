<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Diagnosa;
use App\Models\Pendaftaran;
use App\Models\Obat;
use App\Models\Resep;
use App\Models\ResepDetail;
use App\Models\StokTransaksi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DiagnosaController extends Controller
{
    public function index()
    {
        $query = Pendaftaran::with(['pasien', 'poli', 'dokter'])->orderBy('created_at', 'desc');

        // Jika login sebagai dokter, hanya tampilkan pasien yang mendaftar ke dokter tersebut
        if (Auth::user()->role == 'dokter' && Auth::user()->dokter) {
            $query->where('id_dokter', Auth::user()->dokter->id_dokter);
        }

        $pendaftarans = $query->paginate(10);
        return view('diagnosa.index', compact('pendaftarans'));
    }

    public function create(Request $request)
    {
        $id_pendaftaran = $request->query('pendaftaran');
        if (!$id_pendaftaran) {
            return redirect()->route('diagnosa.index')->withErrors(['error' => 'Pilih pasien dari antrian terlebih dahulu.']);
        }

        $pendaftaran = Pendaftaran::with(['pasien', 'poli'])->findOrFail($id_pendaftaran);
        
        // Update status pendaftaran menjadi diproses jika masih baru
        if ($pendaftaran->status_pendaftaran == 'baru') {
            $pendaftaran->update(['status_pendaftaran' => 'diproses']);
        }

        $obats = Obat::where('stok_tersedia', '>', 0)->orderBy('nama_obat')->get();
        return view('diagnosa.create', compact('pendaftaran', 'obats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_pendaftaran' => 'required|exists:pendaftarans,id_pendaftaran',
            'diagnosa_text' => 'required|string',
            'tindakan' => 'required|string',
            'obat_id' => 'nullable|array',
            'obat_id.*' => 'nullable|exists:obats,id_obat',
            'jumlah' => 'nullable|array',
            'dosis' => 'nullable|array',
        ]);

        $pendaftaran = Pendaftaran::findOrFail($request->id_pendaftaran);
        
        DB::beginTransaction();
        try {
            // 1. Simpan Diagnosa
            $diagnosa = Diagnosa::create([
                'diagnosa_text' => $request->diagnosa_text,
                'tindakan' => $request->tindakan,
                'tanggal_periksa' => now(),
                'id_pendaftaran' => $pendaftaran->id_pendaftaran,
                'id_dokter' => $pendaftaran->id_dokter,
            ]);

            // 2. Simpan Resep & Detail (Jika ada obat yang diresepkan)
            if ($request->has('obat_id') && count($request->obat_id) > 0) {
                // Generate Nomor Resep berurutan per hari
                $todayResepCount = Resep::whereDate('created_at', Carbon::today())->count();
                $nextResep = $todayResepCount + 1;
                $nomor_resep = 'RSP-' . date('Ymd') . '-' . str_pad($nextResep, 4, '0', STR_PAD_LEFT);
                
                $resep = Resep::create([
                    'nomor_resep' => $nomor_resep,
                    'tanggal_resep' => now(),
                    'id_diagnosa' => $diagnosa->id_diagnosa,
                    'id_dokter' => $pendaftaran->id_dokter,
                ]);

                foreach ($request->obat_id as $key => $id_obat) {
                    if (empty($id_obat)) continue; // skip row obat kosong

                    $jumlah = $request->jumlah[$key] ?? 1;
                    $dosis = $request->dosis[$key] ?? '1x1';

                    // Cek stok
                    $obat = Obat::findOrFail($id_obat);
                    if ($obat->stok_tersedia < $jumlah) {
                        throw new \Exception("Stok obat {$obat->nama_obat} tidak mencukupi. Tersedia: {$obat->stok_tersedia}, Diminta: {$jumlah}");
                    }

                    // Simpan Detail Resep
                    ResepDetail::create([
                        'id_resep' => $resep->id_resep,
                        'id_obat' => $id_obat,
                        'jumlah' => $jumlah,
                        'dosis_aturan_pakai' => $dosis,
                    ]);

                    // Kurangi stok di Master Obat
                    $obat->decrement('stok_tersedia', $jumlah);

                    // FEFO (First Expired, First Out): Kurangi sisa_stok pada batch transaksi masuk yang tanggal kadaluarsanya paling awal
                    $remainingToDeduct = $jumlah;
                    $batches = StokTransaksi::where('id_obat', $id_obat)
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
            }

            // 3. Update status pendaftaran menjadi selesai
            $pendaftaran->update(['status_pendaftaran' => 'selesai']);

            DB::commit();
            return redirect()->route('diagnosa.index')->with('success', 'Pemeriksaan Pasien selesai dan Diagnosa/Resep telah disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Gagal menyimpan pemeriksaan: ' . $e->getMessage()]);
        }
    }

    public function show(string $id)
    {
        $diagnosa = Diagnosa::with(['pendaftaran.pasien', 'pendaftaran.dokter', 'resep.resep_details.obat'])->findOrFail($id);
        return view('diagnosa.show', compact('diagnosa'));
    }

    public function edit(string $id)
    {
        // Fitur edit opsional, biasanya data rekam medis yang sudah difinalisasi tidak diubah sembarangan
        return redirect()->route('diagnosa.index')->with('warning', 'Data pemeriksaan yang telah selesai tidak dapat diubah demi objektivitas rekam medis.');
    }

    public function update(Request $request, string $id)
    {
        // ...
    }

    public function destroy(string $id)
    {
        $diagnosa = Diagnosa::findOrFail($id);
        
        DB::beginTransaction();
        try {
            // Restore status pendaftaran
            Pendaftaran::where('id_pendaftaran', $diagnosa->id_pendaftaran)->update(['status_pendaftaran' => 'diproses']);
            
            // Refund stok obat if resep exists
            if ($diagnosa->resep) {
                foreach ($diagnosa->resep->resep_details as $detail) {
                    // Kembalikan stok total di master obat
                    Obat::where('id_obat', $detail->id_obat)->increment('stok_tersedia', $detail->jumlah);

                    // Restore sisa_stok ke batch yang sama persis seperti urutan FEFO saat mengambil
                    // (expired paling awal → dikembalikan duluan, sama seperti saat dikurangi)
                    $remainingToRestore = $detail->jumlah;

                    $batches = StokTransaksi::where('id_obat', $detail->id_obat)
                        ->where('jenis_transaksi', 'masuk')
                        ->orderByRaw('CASE WHEN tanggal_kadaluarsa IS NULL THEN 1 ELSE 0 END, tanggal_kadaluarsa ASC, id_stok_transaksi ASC')
                        ->get();

                    foreach ($batches as $batch) {
                        if ($remainingToRestore <= 0) break;

                        // Kapasitas ruang yang bisa dikembalikan ke batch ini
                        // (tidak boleh melebihi jumlah awal batch)
                        $ruangTersisa = $batch->jumlah - $batch->sisa_stok;

                        if ($ruangTersisa <= 0) continue; // batch ini sudah penuh, skip

                        $toRestore = min($remainingToRestore, $ruangTersisa);
                        $batch->increment('sisa_stok', $toRestore);
                        $remainingToRestore -= $toRestore;
                    }
                }
            }

            $diagnosa->delete();

            DB::commit();
            return redirect()->route('diagnosa.index')->with('success', 'Data Pemeriksaan berhasil dibatalkan/dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal membatalkan pemeriksaan: ' . $e->getMessage()]);
        }
    }
}
