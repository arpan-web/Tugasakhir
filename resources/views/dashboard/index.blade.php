@extends('layouts.app')

@section('title', 'Dashboard Poliklinik')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h2 class="fw-bold text-slate-800 m-0" style="font-size: 1.75rem; color: #0f172a;">Dashboard Poliklinik POLNEP</h2>
    </div>

    <!-- Alert Peringatan Stok Obat -->
    @if(isset($stok_kritis) && count($stok_kritis) > 0)
        <div class="alert alert-warning alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-left: 4px solid var(--warning-color) !important; background-color: rgba(245, 158, 11, 0.05);">
            <div class="d-flex align-items-start">
                <i data-feather="alert-triangle" class="text-warning me-2 mt-1" style="width: 20px; height: 20px; stroke-width: 2.5;"></i>
                <div>
                    <strong>Peringatan Stok Obat!</strong><br>
                    Obat berikut hampir habis atau sudah di bawah batas minimum:
                    <ul class="mb-0 mt-2 ps-3">
                        @foreach($stok_kritis as $obat)
                            <li>{{ $obat->nama_obat }} (Sisa: {{ $obat->stok_tersedia }} {{ $obat->satuan }})</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4 mb-4">
        <!-- Pasien Hari Ini -->
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card border-0 h-100 metric-card">
                <div class="card-body d-flex align-items-center justify-content-between p-4">
                    <div>
                        <span class="text-uppercase fw-bold text-muted d-block mb-1" style="font-size: 0.75rem; letter-spacing: 0.75px;">Pasien Hari Ini</span>
                        <h3 class="mb-0 fw-bold" style="font-size: 1.85rem; color: #0f172a;">{{ $pasien_hari_ini ?? 0 }}</h3>
                    </div>
                    <div class="metric-icon" style="background-color: rgba(111, 66, 193, 0.1); color: #6f42c1; width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                        <i data-feather="users" style="width: 24px; height: 24px; stroke-width: 2;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Antrian Menunggu -->
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card border-0 h-100 metric-card">
                <div class="card-body d-flex align-items-center justify-content-between p-4">
                    <div>
                        <span class="text-uppercase fw-bold text-muted d-block mb-1" style="font-size: 0.75rem; letter-spacing: 0.75px;">Antrian Menunggu</span>
                        <h3 class="mb-0 fw-bold" style="font-size: 1.85rem; color: #0f172a;">{{ $antrian_menunggu ?? 0 }}</h3>
                    </div>
                    <div class="metric-icon" style="background-color: rgba(16, 185, 129, 0.1); color: #10b981; width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                        <i data-feather="clock" style="width: 24px; height: 24px; stroke-width: 2;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stok Obat Minimal -->
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card border-0 h-100 metric-card">
                <div class="card-body d-flex align-items-center justify-content-between p-4">
                    <div>
                        <span class="text-uppercase fw-bold text-muted d-block mb-1" style="font-size: 0.75rem; letter-spacing: 0.75px;">Stok Obat Kritis</span>
                        <h3 class="mb-0 fw-bold" style="font-size: 1.85rem; color: #0f172a;">{{ isset($stok_kritis) ? count($stok_kritis) : 0 }}</h3>
                    </div>
                    <div class="metric-icon" style="background-color: rgba(245, 158, 11, 0.1); color: #f59e0b; width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                        <i data-feather="alert-circle" style="width: 24px; height: 24px; stroke-width: 2;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Kunjungan -->
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card border-0 h-100 metric-card">
                <div class="card-body d-flex align-items-center justify-content-between p-4">
                    <div>
                        <span class="text-uppercase fw-bold text-muted d-block mb-1" style="font-size: 0.75rem; letter-spacing: 0.75px;">Total Kunjungan</span>
                        <h3 class="mb-0 fw-bold" style="font-size: 1.85rem; color: #0f172a;">{{ $total_kunjungan ?? 0 }}</h3>
                    </div>
                    <div class="metric-icon" style="background-color: rgba(214, 51, 132, 0.1); color: #d63384; width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                        <i data-feather="activity" style="width: 24px; height: 24px; stroke-width: 2;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Row Bawah -->
    <div class="row">
        <!-- Antrian Table -->
        <div class="col-lg-7 mb-4">
            <div class="card border-0 h-100">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold" style="color: #0f172a;"><i data-feather="list" class="me-2 text-primary" style="width: 18px; height: 18px; top: -1px; position: relative;"></i>Daftar Antrian Hari Ini</h5>
                </div>
                <div class="card-body px-4">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>No. Antrian</th>
                                    <th>Nama Pasien</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($antrian_list ?? [] as $antri)
                                <tr>
                                    <td><span class="badge bg-primary px-3 py-2 rounded-pill fw-bold">{{ str_pad($antri->no_antrian, 3, '0', STR_PAD_LEFT) }}</span></td>
                                    <td class="fw-semibold text-dark">{{ $antri->pasien->nama_lengkap ?? 'Unknown' }}</td>
                                    <td>
                                        @if($antri->status_pendaftaran == 'baru')
                                            <span class="badge bg-info">Menunggu Panggilan</span>
                                        @elseif($antri->status_pendaftaran == 'diproses')
                                            <span class="badge bg-warning">Sedang Periksa</span>
                                        @else
                                            <span class="badge bg-success">Selesai</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">Belum ada antrian saat ini</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Poliklinik -->
        <div class="col-lg-5 mb-4">
            <div class="card border-0 h-100">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                    <h5 class="mb-0 fw-bold" style="color: #0f172a;"><i data-feather="info" class="me-2 text-primary" style="width: 18px; height: 18px; top: -1px; position: relative;"></i>Informasi Poliklinik</h5>
                </div>
                <div class="card-body px-4 pt-3">
                    <div class="info-box p-3 mb-3" style="background-color: #f8fafc; border-radius: 12px; border: 1px solid #f1f5f9;">
                        <div class="d-flex align-items-center mb-3">
                            <div class="info-icon me-3" style="background-color: rgba(59, 130, 246, 0.1); color: #3b82f6; width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                <i data-feather="clock" style="width: 18px; height: 18px;"></i>
                            </div>
                            <div>
                                <span class="d-block text-muted" style="font-size: 0.75rem; font-weight: 500;">Jam Operasional</span>
                                <strong style="font-size: 0.9rem; color: #1e293b;">Senin - Jumat (08:00 - 16:00)</strong>
                            </div>
                        </div>

                        <div class="d-flex align-items-center mb-3">
                            <div class="info-icon me-3" style="background-color: rgba(239, 68, 68, 0.1); color: #ef4444; width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                <i data-feather="map-pin" style="width: 18px; height: 18px;"></i>
                            </div>
                            <div>
                                <span class="d-block text-muted" style="font-size: 0.75rem; font-weight: 500;">Lokasi</span>
                                <strong style="font-size: 0.9rem; color: #1e293b;">Samping UPA Bahasa POLNEP</strong>
                            </div>
                        </div>

                        <div class="d-flex align-items-center mb-3">
                            <div class="info-icon me-3" style="background-color: rgba(16, 185, 129, 0.1); color: #10b981; width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                <i data-feather="user-check" style="width: 18px; height: 18px;"></i>
                            </div>
                            <div>
                                <span class="d-block text-muted" style="font-size: 0.75rem; font-weight: 500;">Staff Tersedia</span>
                                <strong style="font-size: 0.9rem; color: #1e293b;">{{ $jumlah_dokter ?? 0 }} Dokter, {{ $jumlah_perawat ?? 0 }} Perawat</strong>
                            </div>
                        </div>

                        <div class="d-flex align-items-center">
                            <div class="info-icon me-3" style="background-color: rgba(245, 158, 11, 0.1); color: #f59e0b; width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                <i data-feather="archive" style="width: 18px; height: 18px;"></i>
                            </div>
                            <div>
                                <span class="d-block text-muted" style="font-size: 0.75rem; font-weight: 500;">Total Obat</span>
                                <strong style="font-size: 0.9rem; color: #1e293b;">{{ $total_obat ?? 0 }} Jenis</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .metric-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .metric-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px -5px rgba(0, 0, 0, 0.08) !important;
    }
</style>
@endsection
