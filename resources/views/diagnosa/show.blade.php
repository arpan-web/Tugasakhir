@extends('layouts.app')

@section('title', 'Detail Pemeriksaan & Rekam Medis')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('diagnosa.index') }}" class="btn btn-outline-secondary"><i data-feather="arrow-left"></i> Kembali ke Antrian</a>
        <button class="btn btn-secondary" onclick="window.print()"><i data-feather="printer"></i> Cetak Rekam Medis</button>
    </div>

    <!-- Informasi Pasien -->
    <div class="card border-0 shadow-sm mb-4 print-area">
        <div class="card-header bg-white border-bottom-2 pt-4">
            <h4 class="mb-0 text-center">HASIL PEMERIKSAAN PASIEN POLIKLINIK POLNEP</h4>
            <hr>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-sm-6">
                    <h6 class="text-muted text-uppercase mb-3">DATA PASIEN</h6>
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td width="30%" class="fw-bold">No. Pasien</td>
                            <td>: {{ $diagnosa->pendaftaran->pasien->nomor_pasien }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Nama Lengkap</td>
                            <td>: {{ $diagnosa->pendaftaran->pasien->nama_lengkap }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">No. Pendaftaran</td>
                            <td>: {{ $diagnosa->pendaftaran->nomor_pendaftaran }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Keluhan</td>
                            <td class="text-danger">: {{ $diagnosa->pendaftaran->keluhan ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-sm-6 text-sm-end">
                    <h6 class="text-muted text-uppercase mb-3">INFO PEMERIKSAAN</h6>
                    <table class="table table-borderless table-sm text-sm-end">
                        <tr>
                            <td>Tgl Periksa :</td>
                            <td class="fw-bold">{{ date('d F Y', strtotime($diagnosa->tanggal_periksa)) }}</td>
                        </tr>
                        <tr>
                            <td>Poliklinik :</td>
                            <td class="fw-bold">{{ $diagnosa->pendaftaran->poli->nama_poli ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Dokter :</td>
                            <td class="fw-bold">{{ $diagnosa->pendaftaran->dokter->nama_dokter ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Vital Sign :</td>
                            <td class="fw-bold">{{ $diagnosa->pendaftaran->suhu_tubuh ?? '-' }}°C / {{ $diagnosa->pendaftaran->berat_badan ?? '-' }}kg</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Hasil Diagnosa -->
            <div class="mb-4">
                <h6 class="text-primary text-uppercase border-bottom pb-2">DIAGNOSA PENYAKIT</h6>
                <div class="p-3 bg-light rounded">
                    {!! nl2br(e($diagnosa->diagnosa_text)) !!}
                </div>
            </div>

            <div class="mb-5">
                <h6 class="text-primary text-uppercase border-bottom pb-2">TINDAKAN / ADVICE</h6>
                <div class="p-3 bg-light rounded">
                    {!! nl2br(e($diagnosa->tindakan)) !!}
                </div>
            </div>

            <!-- Resep Obat -->
            @if($diagnosa->resep)
            <div class="border mt-5 p-4 rounded">
                <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
                    <h5 class="mb-0 text-success"><i data-feather="file-text"></i> SALINAN RESEP (COPY/DUPLICATE)</h5>
                    <div>
                        <strong>No. Resep:</strong> <code>{{ $diagnosa->resep->nomor_resep }}</code>
                    </div>
                </div>
                
                <table class="table table-bordered table-striped">
                    <thead class="table-light">
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th width="40%">Nama Obat</th>
                            <th width="15%" class="text-center">Jumlah</th>
                            <th width="40%">Aturan Pakai (Dosis)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($diagnosa->resep->resep_details as $key => $detail)
                        <tr>
                            <td class="text-center">{{ $key + 1 }}</td>
                            <td class="fw-bold">{{ $detail->obat->nama_obat ?? 'Obat Tidak Diketahui' }}</td>
                            <td class="text-center">{{ $detail->jumlah }} {{ $detail->obat->satuan ?? '' }}</td>
                            <td><em>{{ $detail->dosis_aturan_pakai }}</em></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-3">Resep obat kosong.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @else
            <div class="alert alert-secondary">
                <i data-feather="info"></i> Tidak ada obat yang diresepkan untuk pasien ini.
            </div>
            @endif

            <div class="row mt-5">
                <div class="col-8"></div>
                <div class="col-4 text-center">
                    <p class="mb-5">Pontianak, {{ date('d F Y', strtotime($diagnosa->tanggal_periksa)) }}<br>Dokter Pemeriksa,</p>
                    <p class="mb-0 fw-bold"><u>{{ $diagnosa->pendaftaran->dokter->nama_dokter ?? '____________________' }}</u></p>
                    <p class="small text-muted mb-0">SIP. Rekam Medis Digital Polnep</p>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    .print-area, .print-area * {
        visibility: visible;
    }
    .print-area {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        border: none !important;
        box-shadow: none !important;
    }
    .btn, .navbar, .sidebar {
        display: none !important;
    }
}
</style>
@endsection
