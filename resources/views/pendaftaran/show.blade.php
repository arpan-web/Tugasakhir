@extends('layouts.app')

@section('title', 'Detail Pendaftaran Antrian')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('pendaftaran.index') }}" class="btn btn-outline-secondary">
            <i data-feather="arrow-left"></i> Kembali ke Antrian
        </a>
        @if(in_array(auth()->user()->role, ['admin', 'perawat']))
        <a href="{{ route('pendaftaran.edit', $pendaftaran->id_pendaftaran) }}" class="btn btn-warning">
            <i data-feather="edit-2"></i> Ubah Status / Data
        </a>
        @endif
    </div>

    {{-- Header Card: Nomor Antrian & Status --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-auto">
                    <div class="d-flex align-items-center justify-content-center rounded-circle"
                         style="width: 80px; height: 80px; background: linear-gradient(135deg, #3b82f6, #2563eb); color: white; font-size: 1.75rem; font-weight: 700; box-shadow: 0 8px 20px rgba(59,130,246,0.35);">
                        {{ str_pad($pendaftaran->no_antrian, 3, '0', STR_PAD_LEFT) }}
                    </div>
                </div>
                <div class="col ms-2">
                    <h4 class="fw-bold mb-1" style="color: #0f172a;">
                        {{ $pendaftaran->pasien->nama_lengkap ?? 'Pasien Tidak Diketahui' }}
                    </h4>
                    <span class="text-muted" style="font-size: 0.85rem;">
                        <i data-feather="hash" style="width:14px;height:14px;"></i>
                        {{ $pendaftaran->nomor_pendaftaran }}
                    </span>
                </div>
                <div class="col-auto">
                    @if($pendaftaran->status_pendaftaran == 'baru')
                        <span class="badge bg-info text-dark px-3 py-2" style="font-size: 0.9rem;">
                            <i data-feather="clock" style="width:14px;height:14px;"></i> Menunggu Panggilan
                        </span>
                    @elseif($pendaftaran->status_pendaftaran == 'diproses')
                        <span class="badge bg-warning text-dark px-3 py-2" style="font-size: 0.9rem;">
                            <i data-feather="activity" style="width:14px;height:14px;"></i> Sedang Periksa
                        </span>
                    @elseif($pendaftaran->status_pendaftaran == 'selesai')
                        <span class="badge bg-success px-3 py-2" style="font-size: 0.9rem;">
                            <i data-feather="check-circle" style="width:14px;height:14px;"></i> Selesai
                        </span>
                    @else
                        <span class="badge bg-danger px-3 py-2" style="font-size: 0.9rem;">
                            <i data-feather="x-circle" style="width:14px;height:14px;"></i> Batal
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Info Pasien --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                    <h6 class="fw-bold mb-0" style="color: #3b82f6;">
                        <i data-feather="user" style="width:16px;height:16px;"></i> DATA PASIEN
                    </h6>
                </div>
                <div class="card-body px-4 pt-3">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td class="fw-semibold text-muted" style="width:45%;">No. Rekam Medis</td>
                            <td class="fw-bold text-dark">{{ $pendaftaran->pasien->nomor_pasien ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold text-muted">Nama Lengkap</td>
                            <td class="fw-bold text-dark">{{ $pendaftaran->pasien->nama_lengkap ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold text-muted">Jenis Kelamin</td>
                            <td class="text-dark">
                                @if($pendaftaran->pasien->jenis_kelamin == 'L')
                                    Laki-laki
                                @elseif($pendaftaran->pasien->jenis_kelamin == 'P')
                                    Perempuan
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-semibold text-muted">Tanggal Lahir</td>
                            <td class="text-dark">
                                {{ $pendaftaran->pasien->tanggal_lahir
                                    ? \Carbon\Carbon::parse($pendaftaran->pasien->tanggal_lahir)->format('d F Y')
                                    : '-' }}
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-semibold text-muted">No. HP</td>
                            <td class="text-dark">{{ $pendaftaran->pasien->no_hp ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold text-muted">Status Pasien</td>
                            <td>
                                <span class="badge bg-secondary">{{ $pendaftaran->pasien->status_pasien ?? '-' }}</span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        {{-- Info Pendaftaran & Pemeriksaan --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                    <h6 class="fw-bold mb-0" style="color: #10b981;">
                        <i data-feather="clipboard" style="width:16px;height:16px;"></i> INFO PENDAFTARAN
                    </h6>
                </div>
                <div class="card-body px-4 pt-3">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td class="fw-semibold text-muted" style="width:45%;">Tanggal Daftar</td>
                            <td class="text-dark">
                                {{ \Carbon\Carbon::parse($pendaftaran->tanggal_daftar)->format('d F Y') }}
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-semibold text-muted">Poliklinik Tujuan</td>
                            <td class="fw-bold text-dark">{{ $pendaftaran->poli->nama_poli ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold text-muted">Dokter Jaga</td>
                            <td class="text-dark">Dr. {{ $pendaftaran->dokter->nama_dokter ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold text-muted">Perawat</td>
                            <td class="text-dark">{{ $pendaftaran->perawat->nama_perawat ?? 'Admin/Staf' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        {{-- Keluhan Pasien --}}
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                    <h6 class="fw-bold mb-0" style="color: #ef4444;">
                        <i data-feather="message-square" style="width:16px;height:16px;"></i> KELUHAN PASIEN
                    </h6>
                </div>
                <div class="card-body px-4 pt-3">
                    <div class="p-3 rounded" style="background-color: #fef2f2; border: 1px solid #fecaca;">
                        <p class="mb-0" style="color: #7f1d1d; line-height: 1.7;">
                            {{ $pendaftaran->keluhan ?? 'Tidak ada keterangan keluhan.' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pemeriksaan Fisik Awal --}}
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                    <h6 class="fw-bold mb-0" style="color: #f59e0b;">
                        <i data-feather="thermometer" style="width:16px;height:16px;"></i> PEMERIKSAAN FISIK AWAL
                    </h6>
                </div>
                <div class="card-body px-4 pt-3">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="p-3 rounded text-center"
                                 style="background: linear-gradient(135deg, rgba(239,68,68,0.08), rgba(239,68,68,0.04)); border: 1px solid rgba(239,68,68,0.2);">
                                <div style="font-size: 0.75rem; font-weight: 600; color: #ef4444; letter-spacing: 1px; text-transform: uppercase;">Suhu Tubuh</div>
                                <div class="fw-bold mt-1" style="font-size: 1.5rem; color: #0f172a;">
                                    {{ $pendaftaran->suhu_tubuh ? $pendaftaran->suhu_tubuh . ' °C' : '-' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 rounded text-center"
                                 style="background: linear-gradient(135deg, rgba(16,185,129,0.08), rgba(16,185,129,0.04)); border: 1px solid rgba(16,185,129,0.2);">
                                <div style="font-size: 0.75rem; font-weight: 600; color: #10b981; letter-spacing: 1px; text-transform: uppercase;">Berat Badan</div>
                                <div class="fw-bold mt-1" style="font-size: 1.5rem; color: #0f172a;">
                                    {{ $pendaftaran->berat_badan ? $pendaftaran->berat_badan . ' kg' : '-' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 rounded text-center"
                                 style="background: linear-gradient(135deg, rgba(59,130,246,0.08), rgba(59,130,246,0.04)); border: 1px solid rgba(59,130,246,0.2);">
                                <div style="font-size: 0.75rem; font-weight: 600; color: #3b82f6; letter-spacing: 1px; text-transform: uppercase;">Tinggi Badan</div>
                                <div class="fw-bold mt-1" style="font-size: 1.5rem; color: #0f172a;">
                                    {{ $pendaftaran->tinggi_badan ? $pendaftaran->tinggi_badan . ' cm' : '-' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tombol Aksi Bawah --}}
        @if(in_array(auth()->user()->role, ['admin', 'perawat']))
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted" style="font-size: 0.85rem;">Lakukan tindakan lebih lanjut untuk antrian ini:</span>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('pendaftaran.edit', $pendaftaran->id_pendaftaran) }}" class="btn btn-warning">
                            <i data-feather="edit-2" style="width:16px;height:16px;"></i> Ubah Status
                        </a>
                        <form action="{{ route('pendaftaran.destroy', $pendaftaran->id_pendaftaran) }}" method="POST"
                              onsubmit="return confirm('Yakin ingin membatalkan/menghapus pendaftaran ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i data-feather="trash-2" style="width:16px;height:16px;"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
