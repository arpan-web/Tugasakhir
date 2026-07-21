@extends('layouts.app')

@section('title', 'Laporan Diagnosa Penyakit')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4 d-print-none">
        <h2>Laporan Diagnosa Penyakit</h2>
        <button onclick="window.print()" class="btn btn-secondary"><i data-feather="printer"></i> Cetak Laporan</button>
    </div>

    <!-- Filter Form -->
    <div class="card border-0 shadow-sm mb-4 d-print-none">
        <div class="card-body">
            <form action="{{ route('laporan.diagnosa') }}" method="GET" class="row g-3 align-items-end">
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
                    <a href="{{ route('laporan.diagnosa') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Area Cetak Laporan -->
    <div class="card border-0 shadow-sm print-container">
        <div class="card-body p-4">
            <div class="text-center mb-4 print-header">
                <h4 class="mb-1 fw-bold">LAPORAN REKAPITULASI DIAGNOSA PENYAKIT</h4>
                <h5 class="mb-2">POLIKLINIK POLITEKNIK NEGERI PONTIANAK (POLNEP)</h5>
                <p class="text-muted">Periode: {{ date('d F Y', strtotime($startDate)) }} s/d {{ date('d F Y', strtotime($endDate)) }}</p>
                <hr style="border-top: 2px solid #000;">
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-sm">
                    <thead class="table-light text-center">
                        <tr>
                            <th width="5%">No</th>
                            <th width="15%">Tanggal Periksa</th>
                            <th width="15%">Nama Pasien</th>
                            <th width="35%">Hasil Diagnosa / Penyakit</th>
                            <th width="15%">Dokter</th>
                            <th width="15%">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($diagnosas as $index => $diag)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="text-center">{{ date('d/m/Y', strtotime($diag->tanggal_periksa)) }}</td>
                            <td>{{ $diag->pendaftaran->pasien->nama_lengkap ?? '-' }}</td>
                            <td>{{ Str::limit($diag->diagnosa_text, 80) }}</td>
                            <td>{{ $diag->dokter->nama_dokter ?? '-' }}</td>
                            <td class="text-center"><span class="small">{{ Str::limit($diag->tindakan, 40) }}</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">Tidak ada data diagnosa pasien pada rentang waktu ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
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
    }
</style>
@endsection
