<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Pendaftaran;
use App\Models\Diagnosa;
use App\Models\ResepDetail;
use App\Models\StokTransaksi;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index()
    {
        return view('laporan.index');
    }

    public function kunjungan(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $pendaftarans = Pendaftaran::with(['pasien', 'poli', 'dokter'])
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->orderBy('created_at', 'asc')
            ->get();
            
        return view('laporan.kunjungan', compact('pendaftarans', 'startDate', 'endDate'));
    }

    public function diagnosa(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $diagnosas = Diagnosa::with(['pendaftaran.pasien', 'dokter'])
            ->whereDate('tanggal_periksa', '>=', $startDate)
            ->whereDate('tanggal_periksa', '<=', $endDate)
            ->orderBy('tanggal_periksa', 'asc')
            ->get();

        return view('laporan.diagnosa', compact('diagnosas', 'startDate', 'endDate'));
    }

    public function obat(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // Penggunaan Obat dari Resep
        $penggunaanObat = ResepDetail::with('obat')
            ->join('reseps', 'resep_details.id_resep', '=', 'reseps.id_resep')
            ->selectRaw('resep_details.id_obat, SUM(resep_details.jumlah) as total_digunakan')
            ->whereDate('reseps.tanggal_resep', '>=', $startDate)
            ->whereDate('reseps.tanggal_resep', '<=', $endDate)
            ->groupBy('resep_details.id_obat')
            ->get();

        // Riwayat Stok Transaksi Masuk/Keluar
        $stokTransaksis = StokTransaksi::with('obat')
            ->whereDate('tanggal_transaksi', '>=', $startDate)
            ->whereDate('tanggal_transaksi', '<=', $endDate)
            ->orderBy('tanggal_transaksi', 'asc')
            ->get();

        return view('laporan.obat', compact('penggunaanObat', 'stokTransaksis', 'startDate', 'endDate'));
    }
}
