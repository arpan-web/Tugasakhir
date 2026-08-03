<?php

namespace App\Exports;

use App\Models\ResepDetail;
use App\Models\StokTransaksi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ObatExport implements WithMultipleSheets
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate   = $endDate;
    }

    public function sheets(): array
    {
        return [
            new ObatResepSheet($this->startDate, $this->endDate),
            new ObatMutasiSheet($this->startDate, $this->endDate),
        ];
    }
}

// ─── Sheet 1: Penggunaan Resep ───────────────────────────────────────────────
class ObatResepSheet implements FromCollection, WithHeadings, WithTitle, WithStyles, ShouldAutoSize
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate   = $endDate;
    }

    public function collection()
    {
        return ResepDetail::with('obat')
            ->join('reseps', 'resep_details.id_resep', '=', 'reseps.id_resep')
            ->selectRaw('resep_details.id_obat, SUM(resep_details.jumlah) as total_digunakan')
            ->whereDate('reseps.tanggal_resep', '>=', $this->startDate)
            ->whereDate('reseps.tanggal_resep', '<=', $this->endDate)
            ->groupBy('resep_details.id_obat')
            ->get()
            ->map(function ($item, $index) {
                return [
                    'No'            => $index + 1,
                    'Kode Obat'     => $item->obat->kode_obat ?? '-',
                    'Nama Obat'     => $item->obat->nama_obat ?? 'Unknown',
                    'Total Keluar'  => $item->total_digunakan,
                    'Satuan'        => $item->obat->satuan ?? '-',
                ];
            });
    }

    public function headings(): array
    {
        return ['No', 'Kode Obat', 'Nama Obat', 'Total Obat Keluar (ke Pasien)', 'Satuan'];
    }

    public function title(): string { return 'A. Rekap Resep Dokter'; }

    public function styles(Worksheet $sheet)
    {
        return [1 => ['font' => ['bold' => true]]];
    }
}

// ─── Sheet 2: Mutasi Logistik ────────────────────────────────────────────────
class ObatMutasiSheet implements FromCollection, WithHeadings, WithTitle, WithStyles, ShouldAutoSize
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate   = $endDate;
    }

    public function collection()
    {
        return StokTransaksi::with('obat')
            ->whereDate('tanggal_transaksi', '>=', $this->startDate)
            ->whereDate('tanggal_transaksi', '<=', $this->endDate)
            ->orderBy('tanggal_transaksi', 'asc')
            ->get()
            ->map(function ($item, $index) {
                return [
                    'No'            => $index + 1,
                    'Tanggal'       => \Carbon\Carbon::parse($item->tanggal_transaksi)->format('d/m/Y'),
                    'Nama Obat'     => $item->obat->nama_obat ?? '-',
                    'Tipe Mutasi'   => ucfirst($item->jenis_transaksi),
                    'Jumlah'        => $item->jumlah,
                    'Satuan'        => $item->obat->satuan ?? '-',
                    'Keterangan'    => $item->keterangan ?? '-',
                ];
            });
    }

    public function headings(): array
    {
        return ['No', 'Tanggal', 'Nama Obat', 'Tipe Mutasi', 'Jumlah', 'Satuan', 'Keterangan'];
    }

    public function title(): string { return 'B. Mutasi Logistik'; }

    public function styles(Worksheet $sheet)
    {
        return [1 => ['font' => ['bold' => true]]];
    }
}
