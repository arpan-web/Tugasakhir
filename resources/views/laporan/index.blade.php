@extends('layouts.app')

@section('title', 'Laporan Manajemen Poliklinik')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Modul Laporan Eksekutif</h2>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center py-5">
                    <div class="mb-3">
                        <i data-feather="users" class="text-primary" style="width: 48px; height: 48px;"></i>
                    </div>
                    <h5 class="card-title fw-bold">Laporan Kunjungan Pasien</h5>
                    <p class="text-muted small">Rekapitulasi jumlah pasien dan antrean harian gabungan dari seluruh poli.</p>
                    <a href="{{ route('laporan.kunjungan') }}" class="btn btn-outline-primary mt-3">Buka Laporan</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center py-5">
                    <div class="mb-3">
                        <i data-feather="activity" class="text-danger" style="width: 48px; height: 48px;"></i>
                    </div>
                    <h5 class="card-title fw-bold">Laporan Diagnosa Penyakit</h5>
                    <p class="text-muted small">Sebaran diagnosa medis dan tren penyakit yang terekam pada periode tertentu.</p>
                    <a href="{{ route('laporan.diagnosa') }}" class="btn btn-outline-danger mt-3">Buka Laporan</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center py-5">
                    <div class="mb-3">
                        <i data-feather="archive" class="text-success" style="width: 48px; height: 48px;"></i>
                    </div>
                    <h5 class="card-title fw-bold">Laporan Penggunaan Obat</h5>
                    <p class="text-muted small">Data mutasi masuk/keluar serta serapan obat dari peresepan dokter.</p>
                    <a href="{{ route('laporan.obat') }}" class="btn btn-outline-success mt-3">Buka Laporan</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
