<?php

namespace App\Exports;

use App\Models\Pendaftaran;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class KunjunganExport implements FromCollection, WithHeadings, WithTitle, WithStyles, ShouldAutoSize
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
        return Pendaftaran::with(['pasien', 'poli', 'dokter'])
            ->whereDate('created_at', '>=', $this->startDate)
            ->whereDate('created_at', '<=', $this->endDate)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($item, $index) {
                return [
                    'No'              => $index + 1,
                    'Tanggal Daftar'  => $item->created_at->format('d/m/Y H:i'),
                    'No Registrasi'   => $item->nomor_pendaftaran,
                    'Nama Pasien'     => $item->pasien->nama_lengkap ?? '-',
                    'Poliklinik'      => $item->poli->nama_poli ?? '-',
                    'Dokter Pemeriksa'=> $item->dokter->nama_dokter ?? '-',
                    'Status'          => ucfirst($item->status_pendaftaran),
                ];
            });
    }

    public function headings(): array
    {
        return ['No', 'Tanggal Daftar', 'No Registrasi', 'Nama Pasien', 'Poliklinik', 'Dokter Pemeriksa', 'Status'];
    }

    public function title(): string
    {
        return 'Laporan Kunjungan';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
