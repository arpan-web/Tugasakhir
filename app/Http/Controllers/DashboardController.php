<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Pendaftaran;
use App\Models\Obat;
use App\Models\Dokter;
use App\Models\Perawat;
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
        // Ambil obat dengan stok_tersedia <= stok_minimal
        $stok_kritis = Obat::whereColumn('stok_tersedia', '<=', 'stok_minimal')->get();

        // 4. Total Kunjungan Keseluruhan Waktu
        $total_kunjungan = Pendaftaran::count();

        // 5. Daftar Antrian Hari ini Lengkap
        $antrian_list = Pendaftaran::with('pasien')
                                   ->whereDate('tanggal_daftar', $today)
                                   ->orderBy('no_antrian', 'asc')
                                   ->get();

        // 6. Informasi Staff Tambahan
        $jumlah_dokter = Dokter::count();
        $jumlah_perawat = Perawat::count();
        $total_obat = Obat::count();

        return view('dashboard.index', compact(
            'pasien_hari_ini',
            'antrian_menunggu',
            'stok_kritis',
            'total_kunjungan',
            'antrian_list',
            'jumlah_dokter',
            'jumlah_perawat',
            'total_obat'
        ));
    }
}
