<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pendaftaran;
use App\Models\Obat;
use App\Models\Dokter;
use App\Models\Perawat;
use App\Models\ResepDetail;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // 1. Data Pasien Hari Ini
        $pasien_hari_ini = Pendaftaran::whereDate('tanggal_daftar', $today)->count();

        // 2. Antrian Menunggu
        $antrian_menunggu = Pendaftaran::whereDate('tanggal_daftar', $today)
                                       ->where('status_pendaftaran', 'baru')
                                       ->count();

        // 3. Stok Obat Kritis
        $stok_kritis = Obat::whereColumn('stok_tersedia', '<=', 'stok_minimal')->get();

        // 3.b Stok Obat Mendekati / Sudah Kadaluarsa
        $stok_expired = \App\Models\StokTransaksi::with('obat')
            ->where('jenis_transaksi', 'masuk')
            ->where('sisa_stok', '>', 0)
            ->whereNotNull('tanggal_kadaluarsa')
            ->whereDate('tanggal_kadaluarsa', '<=', Carbon::today()->addDays(30))
            ->orderBy('tanggal_kadaluarsa', 'asc')
            ->get();

        // 4. Total Kunjungan Keseluruhan Waktu
        $total_kunjungan = Pendaftaran::count();

        // 5. Daftar Antrian Hari ini Lengkap
        $antrian_list = Pendaftaran::with('pasien')
                                   ->whereDate('tanggal_daftar', $today)
                                   ->orderBy('no_antrian', 'asc')
                                   ->get();

        // 6. Informasi Staff
        $jumlah_dokter  = Dokter::count();
        $jumlah_perawat = Perawat::count();
        $total_obat     = Obat::count();

        // 7. CHART: Kunjungan 7 Hari Terakhir
        $kunjungan7Hari = collect(range(6, 0))->map(function ($i) {
            $date = Carbon::today()->subDays($i);
            return [
                'tanggal' => $date->translatedFormat('d M'),
                'jumlah'  => Pendaftaran::whereDate('tanggal_daftar', $date)->count(),
            ];
        });

        $chartKunjunganLabels = $kunjungan7Hari->pluck('tanggal')->toJson();
        $chartKunjunganData   = $kunjungan7Hari->pluck('jumlah')->toJson();

        // 8. CHART: Top 5 Obat Terbanyak Diresepkan
        $topObat = ResepDetail::with('obat')
            ->selectRaw('id_obat, SUM(jumlah) as total_digunakan')
            ->groupBy('id_obat')
            ->orderByDesc('total_digunakan')
            ->limit(5)
            ->get();

        $chartObatLabels = $topObat->map(fn($r) => $r->obat->nama_obat ?? 'Unknown')->toJson();
        $chartObatData   = $topObat->pluck('total_digunakan')->toJson();

        return view('dashboard.index', compact(
            'pasien_hari_ini',
            'antrian_menunggu',
            'stok_kritis',
            'stok_expired',
            'total_kunjungan',
            'antrian_list',
            'jumlah_dokter',
            'jumlah_perawat',
            'total_obat',
            'chartKunjunganLabels',
            'chartKunjunganData',
            'chartObatLabels',
            'chartObatData'
        ));
    }
}
