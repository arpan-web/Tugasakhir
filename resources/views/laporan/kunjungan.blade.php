@extends('layouts.app')

@section('title', 'Laporan Kunjungan Pasien')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4 d-print-none">
        <h2>Laporan Kunjungan Pasien</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('laporan.kunjungan.excel', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
               class="btn btn-success">
                <i data-feather="download" style="width:14px;height:14px;"></i> Excel
            </a>
            <a href="{{ route('laporan.kunjungan.pdf', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
               class="btn btn-danger">
                <i data-feather="file-text" style="width:14px;height:14px;"></i> PDF
            </a>
            <button onclick="window.print()" class="btn btn-secondary">
                <i data-feather="printer" style="width:14px;height:14px;"></i> Cetak
            </button>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="card border-0 shadow-sm mb-4 d-print-none">
        <div class="card-body">
            <form action="{{ route('laporan.kunjungan') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="start_date" class="form-label text-muted small fw-bold">Dari Tanggal</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $startDate }}" required>
                </div>
                <div class="col-md-4">
                    <label for="end_date" class="form-label text-muted small fw-bold">Sampai Tanggal</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $endDate }}" required>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary"><i data-feather="filter" class="icon-sm"></i> Filter Data</button>
                    <a href="{{ route('laporan.kunjungan') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Area Cetak Laporan -->
    <div class="card border-0 shadow-sm print-container">
        <div class="card-body p-4">
            <div class="text-center mb-4 print-header">
                <h4 class="mb-1 fw-bold">LAPORAN KUNJUNGAN PASIEN</h4>
                <h5 class="mb-2">POLIKLINIK POLITEKNIK NEGERI PONTIANAK (POLNEP)</h5>
                <p class="text-muted">Periode: {{ date('d F Y', strtotime($startDate)) }} s/d {{ date('d F Y', strtotime($endDate)) }}</p>
                <hr style="border-top: 2px solid #000;">
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-sm">
                    <thead class="table-light text-center">
                        <tr>
                            <th width="5%">No</th>
                            <th width="15%">Tanggal Daftar</th>
                            <th width="15%">No Registrasi</th>
                            <th width="20%">Nama Pasien</th>
                            <th width="15%">Poliklinik Tujuan</th>
                            <th width="15%">Dokter Pemeriksa</th>
                            <th width="15%">Status Akhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendaftarans as $index => $pdf)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="text-center">{{ $pdf->created_at->format('d/m/Y H:i') }}</td>
                            <td class="text-center">{{ $pdf->nomor_pendaftaran }}</td>
                            <td>{{ $pdf->pasien->nama_lengkap ?? '-' }}</td>
                            <td>{{ $pdf->poli->nama_poli ?? '-' }}</td>
                            <td>{{ $pdf->dokter->nama_dokter ?? '-' }}</td>
                            <td class="text-center">
                                @if($pdf->status_pendaftaran == 'baru')
                                    <span class="badge bg-danger">Menunggu</span>
                                @elseif($pdf->status_pendaftaran == 'diproses')
                                    <span class="badge bg-warning text-dark">Diperiksa</span>
                                @elseif($pdf->status_pendaftaran == 'selesai')
                                    <span class="badge bg-success">Selesai</span>
                                @else
                                    <span class="badge bg-secondary">{{ $pdf->status_pendaftaran }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">Tidak ada data pendaftaran/kunjungan pada periode ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-light fw-bold">
                        <tr>
                            <td colspan="6" class="text-end py-2">Total Kunjungan Pasien Berobat:</td>
                            <td class="text-center py-2">{{ $pendaftarans->count() }} Pasien</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="row mt-5 d-none d-print-flex">
                <div class="col-8"></div>
                <div class="col-4 text-center">
                    <p class="mb-5">Pontianak, {{ date('d F Y') }}<br>Penanggung Jawab,</p>
                    <p class="mb-0 fw-bold"><u>{{ Auth::user()->username }}</u></p>
                    <p class="small text-muted">{{ Auth::user()->role }} Poliklinik</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .icon-sm { width: 14px; height: 14px; }
    @media print {
        body * {
            visibility: hidden;
        }
        .print-container, .print-container * {
            visibility: visible;
        }
        .print-container {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        .d-print-none {
            display: none !important;
        }
        .badge {
            border: 1px solid #000;
            color: #000 !important;
            background: transparent !important;
        }
    }
</style>
@endsection
