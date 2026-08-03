@extends('layouts.app')

@section('title', 'Dashboard Poliklinik')

@section('content')
    <div class="container-fluid">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h2 class="fw-bold m-0" style="font-size: 1.75rem; color: #0f172a;">Dashboard Poliklinik POLNEP</h2>
        </div>

        {{-- Alert Stok Menipis --}}
        @if(isset($stok_kritis) && count($stok_kritis) > 0)
            <div class="alert alert-warning alert-dismissible fade show border-0 shadow-sm mb-3" role="alert"
                style="border-left: 4px solid #f59e0b !important; background-color: rgba(245,158,11,0.05);">
                <div class="d-flex align-items-start">
                    <i data-feather="alert-triangle" class="text-warning me-2 mt-1" style="width:20px;height:20px;stroke-width:2.5;"></i>
                    <div>
                        <strong>Peringatan Stok Obat Menipis!</strong><br>
                        Obat berikut hampir habis atau sudah di bawah batas minimum:
                        <ul class="mb-0 mt-2 ps-3">
                            @foreach($stok_kritis as $obat)
                                <li>{{ $obat->nama_obat }} (Sisa: {{ $obat->stok_tersedia }} {{ $obat->satuan }})</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Alert Kadaluarsa --}}
        @if(isset($stok_expired) && count($stok_expired) > 0)
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert"
                style="border-left: 4px solid #ef4444 !important; background-color: rgba(239,68,68,0.05);">
                <div class="d-flex align-items-start">
                    <i data-feather="calendar" class="text-danger me-2 mt-1" style="width:20px;height:20px;stroke-width:2.5;"></i>
                    <div>
                        <strong>Peringatan Kadaluarsa Obat!</strong><br>
                        Terdapat batch obat yang sudah atau hampir kadaluarsa:
                        <ul class="mb-0 mt-2 ps-3">
                            @foreach($stok_expired as $be)
                                @php
                                    $tglExp  = \Carbon\Carbon::parse($be->tanggal_kadaluarsa);
                                    $hariSisa = \Carbon\Carbon::today()->diffInDays($tglExp, false);
                                    $isExpired = \Carbon\Carbon::today()->gt($tglExp);
                                @endphp
                                <li class="mb-1">
                                    <strong>{{ $be->obat->nama_obat ?? 'Obat' }}</strong>
                                    (Sisa batch: {{ $be->sisa_stok }} {{ $be->obat->satuan ?? '' }}) -
                                    @if($isExpired)
                                        <span class="badge bg-danger">🚫 SUDAH EXPIRED ({{ $tglExp->format('d/m/Y') }})</span>
                                        <a href="{{ route('stok_transaksi.index') }}" class="btn btn-sm btn-outline-danger py-0 px-2 ms-2" style="font-size:11px;">Musnahkan</a>
                                    @else
                                        <span class="badge bg-warning text-dark">⚠ Expired {{ $tglExp->format('d/m/Y') }} ({{ $hariSisa }} hari lagi)</span>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- ===== METRIC CARDS ===== --}}
        <div class="row g-4 mb-4">
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card border-0 h-100 metric-card">
                    <div class="card-body d-flex align-items-center justify-content-between p-4">
                        <div>
                            <span class="text-uppercase fw-bold text-muted d-block mb-1" style="font-size:0.75rem;letter-spacing:0.75px;">Pasien Hari Ini</span>
                            <h3 class="mb-0 fw-bold" style="font-size:1.85rem;color:#0f172a;">{{ $pasien_hari_ini ?? 0 }}</h3>
                        </div>
                        <div class="metric-icon" style="background-color:rgba(111,66,193,0.1);color:#6f42c1;width:50px;height:50px;border-radius:12px;display:flex;align-items:center;justify-content:center;">
                            <i data-feather="users" style="width:24px;height:24px;stroke-width:2;"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-3">
                <div class="card border-0 h-100 metric-card">
                    <div class="card-body d-flex align-items-center justify-content-between p-4">
                        <div>
                            <span class="text-uppercase fw-bold text-muted d-block mb-1" style="font-size:0.75rem;letter-spacing:0.75px;">Antrian Menunggu</span>
                            <h3 class="mb-0 fw-bold" style="font-size:1.85rem;color:#0f172a;">{{ $antrian_menunggu ?? 0 }}</h3>
                        </div>
                        <div class="metric-icon" style="background-color:rgba(16,185,129,0.1);color:#10b981;width:50px;height:50px;border-radius:12px;display:flex;align-items:center;justify-content:center;">
                            <i data-feather="clock" style="width:24px;height:24px;stroke-width:2;"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-3">
                <div class="card border-0 h-100 metric-card">
                    <div class="card-body d-flex align-items-center justify-content-between p-4">
                        <div>
                            <span class="text-uppercase fw-bold text-muted d-block mb-1" style="font-size:0.75rem;letter-spacing:0.75px;">Stok Obat Kritis</span>
                            <h3 class="mb-0 fw-bold" style="font-size:1.85rem;color:#0f172a;">{{ isset($stok_kritis) ? count($stok_kritis) : 0 }}</h3>
                        </div>
                        <div class="metric-icon" style="background-color:rgba(245,158,11,0.1);color:#f59e0b;width:50px;height:50px;border-radius:12px;display:flex;align-items:center;justify-content:center;">
                            <i data-feather="alert-circle" style="width:24px;height:24px;stroke-width:2;"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-3">
                <div class="card border-0 h-100 metric-card">
                    <div class="card-body d-flex align-items-center justify-content-between p-4">
                        <div>
                            <span class="text-uppercase fw-bold text-muted d-block mb-1" style="font-size:0.75rem;letter-spacing:0.75px;">Total Kunjungan</span>
                            <h3 class="mb-0 fw-bold" style="font-size:1.85rem;color:#0f172a;">{{ $total_kunjungan ?? 0 }}</h3>
                        </div>
                        <div class="metric-icon" style="background-color:rgba(214,51,132,0.1);color:#d63384;width:50px;height:50px;border-radius:12px;display:flex;align-items:center;justify-content:center;">
                            <i data-feather="activity" style="width:24px;height:24px;stroke-width:2;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== ANTRIAN + INFO ===== --}}
        <div class="row g-4 mb-4">
            {{-- Antrian Hari Ini --}}
            <div class="col-lg-7">
                <div class="card border-0 h-100">
                    <div class="card-header bg-white border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold" style="color:#0f172a;">
                            <i data-feather="list" class="me-2 text-primary" style="width:18px;height:18px;top:-1px;position:relative;"></i>
                            Daftar Antrian Hari Ini
                        </h5>
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

            {{-- Info Poliklinik --}}
            <div class="col-lg-5">
                <div class="card border-0 h-100">
                    <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                        <h5 class="mb-0 fw-bold" style="color:#0f172a;">
                            <i data-feather="info" class="me-2 text-primary" style="width:18px;height:18px;top:-1px;position:relative;"></i>
                            Informasi Poliklinik
                        </h5>
                    </div>
                    <div class="card-body px-4 pt-3">
                        <div class="p-3" style="background-color:#f8fafc;border-radius:12px;border:1px solid #f1f5f9;">
                            @foreach([
                                ['icon'=>'clock','color'=>'#3b82f6','bg'=>'rgba(59,130,246,0.1)','label'=>'Jam Operasional','val'=>'Senin - Jumat (08:00 - 16:00)'],
                                ['icon'=>'map-pin','color'=>'#ef4444','bg'=>'rgba(239,68,68,0.1)','label'=>'Lokasi','val'=>'Samping UPA Bahasa POLNEP'],
                                ['icon'=>'user-check','color'=>'#10b981','bg'=>'rgba(16,185,129,0.1)','label'=>'Staff Tersedia','val'=>($jumlah_dokter ?? 0).' Dokter, '.($jumlah_perawat ?? 0).' Perawat'],
                                ['icon'=>'archive','color'=>'#f59e0b','bg'=>'rgba(245,158,11,0.1)','label'=>'Total Obat','val'=>($total_obat ?? 0).' Jenis'],
                            ] as $info)
                            <div class="d-flex align-items-center {{ !$loop->last ? 'mb-3' : '' }}">
                                <div class="me-3" style="background-color:{{ $info['bg'] }};color:{{ $info['color'] }};width:36px;height:36px;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i data-feather="{{ $info['icon'] }}" style="width:18px;height:18px;"></i>
                                </div>
                                <div>
                                    <span class="d-block text-muted" style="font-size:0.75rem;font-weight:500;">{{ $info['label'] }}</span>
                                    <strong style="font-size:0.9rem;color:#1e293b;">{{ $info['val'] }}</strong>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== GRAFIK ===== --}}
        <div class="row g-4 mb-4">

            {{-- Grafik 1: Tren Kunjungan 7 Hari --}}
            <div class="col-lg-7">
                <div class="card border-0 h-100">
                    <div class="card-header bg-white border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold" style="color:#0f172a;">
                            <i data-feather="trending-up" class="me-2 text-primary" style="width:18px;height:18px;top:-1px;position:relative;"></i>
                            Tren Kunjungan 7 Hari Terakhir
                        </h5>
                        <span class="badge rounded-pill" style="background-color:rgba(59,130,246,0.1);color:#3b82f6;font-size:0.75rem;font-weight:600;">Harian</span>
                    </div>
                    <div class="card-body px-4 pb-4">
                        <canvas id="chartKunjungan" height="130"></canvas>
                    </div>
                </div>
            </div>

            {{-- Grafik 2: Top 5 Obat --}}
            <div class="col-lg-5">
                <div class="card border-0 h-100">
                    <div class="card-header bg-white border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold" style="color:#0f172a;">
                            <i data-feather="bar-chart-2" class="me-2 text-success" style="width:18px;height:18px;top:-1px;position:relative;"></i>
                            Top 5 Obat Terbanyak
                        </h5>
                        <span class="badge rounded-pill" style="background-color:rgba(16,185,129,0.1);color:#10b981;font-size:0.75rem;font-weight:600;">Semua Waktu</span>
                    </div>
                    <div class="card-body px-4 pb-4">
                        @if(json_decode($chartObatLabels) && count(json_decode($chartObatLabels)) > 0)
                            <canvas id="chartObat" height="200"></canvas>
                        @else
                            <div class="d-flex flex-column align-items-center justify-content-center h-100 text-muted" style="min-height:180px;">
                                <i data-feather="inbox" style="width:40px;height:40px;opacity:0.3;"></i>
                                <p class="mt-2 mb-0" style="font-size:0.85rem;">Belum ada data resep obat</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // Grafik 1: Line - Tren Kunjungan
            const ctx1 = document.getElementById('chartKunjungan');
            if (ctx1) {
                new Chart(ctx1, {
                    type: 'line',
                    data: {
                        labels: {!! $chartKunjunganLabels !!},
                        datasets: [{
                            label: 'Jumlah Kunjungan',
                            data: {!! $chartKunjunganData !!},
                            fill: true,
                            tension: 0.45,
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59,130,246,0.08)',
                            pointBackgroundColor: '#3b82f6',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 5,
                            pointHoverRadius: 7,
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { display: false },
                            tooltip: { callbacks: { label: c => ` ${c.parsed.y} kunjungan` } }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: { stepSize: 1, font: { size: 11 }, color: '#94a3b8' },
                                grid: { color: 'rgba(0,0,0,0.04)' }
                            },
                            x: {
                                ticks: { font: { size: 11 }, color: '#94a3b8' },
                                grid: { display: false }
                            }
                        }
                    }
                });
            }

            // Grafik 2: Horizontal Bar - Top 5 Obat
            const ctx2 = document.getElementById('chartObat');
            const labels2 = {!! $chartObatLabels !!};
            if (ctx2 && labels2.length > 0) {
                new Chart(ctx2, {
                    type: 'bar',
                    data: {
                        labels: labels2,
                        datasets: [{
                            label: 'Total Diresepkan',
                            data: {!! $chartObatData !!},
                            backgroundColor: [
                                'rgba(16,185,129,0.85)',
                                'rgba(59,130,246,0.85)',
                                'rgba(245,158,11,0.85)',
                                'rgba(239,68,68,0.85)',
                                'rgba(139,92,246,0.85)',
                            ],
                            borderRadius: 8,
                            borderSkipped: false,
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        plugins: {
                            legend: { display: false },
                            tooltip: { callbacks: { label: c => ` ${c.parsed.x} unit` } }
                        },
                        scales: {
                            x: {
                                beginAtZero: true,
                                ticks: { stepSize: 1, font: { size: 11 }, color: '#94a3b8' },
                                grid: { color: 'rgba(0,0,0,0.04)' }
                            },
                            y: {
                                ticks: { font: { size: 11 }, color: '#374151' },
                                grid: { display: false }
                            }
                        }
                    }
                });
            }
        });
    </script>

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