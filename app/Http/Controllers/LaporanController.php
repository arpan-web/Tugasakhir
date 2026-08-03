<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pendaftaran;
use App\Models\Diagnosa;
use App\Models\ResepDetail;
use App\Models\StokTransaksi;
use App\Exports\KunjunganExport;
use App\Exports\ObatExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index()
    {
        return view('laporan.index');
    }

    // ────────────────────────────────────────────────────────────────────────
    // LAPORAN KUNJUNGAN
    // ────────────────────────────────────────────────────────────────────────

    public function kunjungan(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate   = $request->input('end_date',   Carbon::now()->endOfMonth()->format('Y-m-d'));

        $pendaftarans = Pendaftaran::with(['pasien', 'poli', 'dokter'])
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('laporan.kunjungan', compact('pendaftarans', 'startDate', 'endDate'));
    }

    public function kunjunganExcel(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate   = $request->input('end_date',   Carbon::now()->endOfMonth()->format('Y-m-d'));

        $filename = 'Laporan_Kunjungan_' . $startDate . '_sd_' . $endDate . '.xlsx';
        return Excel::download(new KunjunganExport($startDate, $endDate), $filename);
    }

    public function kunjunganPdf(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate   = $request->input('end_date',   Carbon::now()->endOfMonth()->format('Y-m-d'));

        $pendaftarans = Pendaftaran::with(['pasien', 'poli', 'dokter'])
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->orderBy('created_at', 'asc')
            ->get();

        $pdf = Pdf::loadView('laporan.pdf_kunjungan', compact('pendaftarans', 'startDate', 'endDate'))
                  ->setPaper('a4', 'landscape');

        $filename = 'Laporan_Kunjungan_' . $startDate . '_sd_' . $endDate . '.pdf';
        return $pdf->download($filename);
    }

    // ────────────────────────────────────────────────────────────────────────
    // LAPORAN DIAGNOSA
    // ────────────────────────────────────────────────────────────────────────

    public function diagnosa(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate   = $request->input('end_date',   Carbon::now()->endOfMonth()->format('Y-m-d'));

        $diagnosas = Diagnosa::with(['pendaftaran.pasien', 'dokter'])
            ->whereDate('tanggal_periksa', '>=', $startDate)
            ->whereDate('tanggal_periksa', '<=', $endDate)
            ->orderBy('tanggal_periksa', 'asc')
            ->get();

        return view('laporan.diagnosa', compact('diagnosas', 'startDate', 'endDate'));
    }

    // ────────────────────────────────────────────────────────────────────────
    // LAPORAN OBAT
    // ────────────────────────────────────────────────────────────────────────

    public function obat(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate   = $request->input('end_date',   Carbon::now()->endOfMonth()->format('Y-m-d'));

        $penggunaanObat = ResepDetail::with('obat')
            ->join('reseps', 'resep_details.id_resep', '=', 'reseps.id_resep')
            ->selectRaw('resep_details.id_obat, SUM(resep_details.jumlah) as total_digunakan')
            ->whereDate('reseps.tanggal_resep', '>=', $startDate)
            ->whereDate('reseps.tanggal_resep', '<=', $endDate)
            ->groupBy('resep_details.id_obat')
            ->get();

        $stokTransaksis = StokTransaksi::with('obat')
            ->whereDate('tanggal_transaksi', '>=', $startDate)
            ->whereDate('tanggal_transaksi', '<=', $endDate)
            ->orderBy('tanggal_transaksi', 'asc')
            ->get();

        return view('laporan.obat', compact('penggunaanObat', 'stokTransaksis', 'startDate', 'endDate'));
    }

    public function obatExcel(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate   = $request->input('end_date',   Carbon::now()->endOfMonth()->format('Y-m-d'));

        $filename = 'Laporan_Obat_' . $startDate . '_sd_' . $endDate . '.xlsx';
        return Excel::download(new ObatExport($startDate, $endDate), $filename);
    }

    public function obatPdf(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate   = $request->input('end_date',   Carbon::now()->endOfMonth()->format('Y-m-d'));

        $penggunaanObat = ResepDetail::with('obat')
            ->join('reseps', 'resep_details.id_resep', '=', 'reseps.id_resep')
            ->selectRaw('resep_details.id_obat, SUM(resep_details.jumlah) as total_digunakan')
            ->whereDate('reseps.tanggal_resep', '>=', $startDate)
            ->whereDate('reseps.tanggal_resep', '<=', $endDate)
            ->groupBy('resep_details.id_obat')
            ->get();

        $stokTransaksis = StokTransaksi::with('obat')
            ->whereDate('tanggal_transaksi', '>=', $startDate)
            ->whereDate('tanggal_transaksi', '<=', $endDate)
            ->orderBy('tanggal_transaksi', 'asc')
            ->get();

        $pdf = Pdf::loadView('laporan.pdf_obat', compact('penggunaanObat', 'stokTransaksis', 'startDate', 'endDate'))
                  ->setPaper('a4', 'landscape');

        $filename = 'Laporan_Obat_' . $startDate . '_sd_' . $endDate . '.pdf';
        return $pdf->download($filename);
    }
}
