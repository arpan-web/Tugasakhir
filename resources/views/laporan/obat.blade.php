@extends('layouts.app')

@section('title', 'Laporan Penggunaan Obat')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4 d-print-none">
        <h2>Laporan Penggunaan dan Mutasi Obat</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('laporan.obat.excel', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
               class="btn btn-success">
                <i data-feather="download" style="width:14px;height:14px;"></i> Excel
            </a>
            <a href="{{ route('laporan.obat.pdf', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
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
            <form action="{{ route('laporan.obat') }}" method="GET" class="row g-3 align-items-end">
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
                    <a href="{{ route('laporan.obat') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Area Cetak Laporan -->
    <div class="card border-0 shadow-sm print-container">
        <div class="card-body p-4">
            <div class="text-center mb-4 print-header">
                <h4 class="mb-1 fw-bold">LAPORAN MUTASI DAN PENGGUNAAN OBAT/FARMASI</h4>
                <h5 class="mb-2">POLIKLINIK POLITEKNIK NEGERI PONTIANAK (POLNEP)</h5>
                <p class="text-muted">Periode: {{ date('d F Y', strtotime($startDate)) }} s/d {{ date('d F Y', strtotime($endDate)) }}</p>
                <hr style="border-top: 2px solid #000;">
            </div>

            <!-- Bagian 1: Penggunaan Resep Dokter -->
            <h5 class="mt-4 mb-3 text-success border-bottom pb-2">A. Rekap Total Obat dari Resep Dokter</h5>
            <div class="table-responsive mb-5">
                <table class="table table-bordered table-striped table-sm">
                    <thead class="table-light text-center">
                        <tr>
                            <th width="10%">No</th>
                            <th width="20%">Kode Obat</th>
                            <th width="40%">Nama Obat</th>
                            <th width="30%">Total Obat Keluar (ke Pasien)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($penggunaanObat as $index => $penggunaan)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="text-center"><code>{{ $penggunaan->obat->kode_obat ?? '-' }}</code></td>
                            <td class="fw-bold">{{ $penggunaan->obat->nama_obat ?? 'Unknown' }}</td>
                            <td class="text-center fw-bold text-danger">{{ $penggunaan->total_digunakan }} {{ $penggunaan->obat->satuan ?? 'pcs' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4">Belum ada obat yang diresepkan pada periode ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Bagian 2: Riwayat Logistik Master -->
            <h5 class="mt-5 mb-3 text-primary border-bottom pb-2">B. Riwayat Mutasi Logistik Master Farmasi</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-sm">
                    <thead class="table-light text-center">
                        <tr>
                            <th width="5%">No</th>
                            <th width="15%">Tanggal Trx</th>
                            <th width="30%">Nama Obat</th>
                            <th width="15%">Tipe Mutasi</th>
                            <th width="10%">Jumlah</th>
                            <th width="25%">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stokTransaksis as $index => $trx)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="text-center">{{ date('d/m/Y', strtotime($trx->tanggal_transaksi)) }}</td>
                            <td>{{ $trx->obat->nama_obat ?? '-' }}</td>
                            <td class="text-center">
                                @if($trx->jenis_transaksi == 'masuk')
                                    <span class="badge" style="border:1px solid #198754; color:#198754;">Masuk</span>
                                @else
                                    <span class="badge" style="border:1px solid #dc3545; color:#dc3545;">Keluar</span>
                                @endif
                            </td>
                            <td class="text-center">{{ $trx->jumlah }} {{ $trx->obat->satuan ?? '' }}</td>
                            <td><small>{{ $trx->keterangan ?? '-' }}</small></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">Tidak ada catatan restock alat/obat pada periode ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="row mt-5 d-none d-print-flex">
                <div class="col-8"></div>
                <div class="col-4 text-center">
                    <p class="mb-5">Pontianak, {{ date('d F Y') }}<br>Apoteker / Bagian Logistik,</p>
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
