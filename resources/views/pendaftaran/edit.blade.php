@extends('layouts.app')

@section('title', 'Ubah Status Pendaftaran')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <a href="{{ route('pendaftaran.show', $pendaftaran->id_pendaftaran) }}" class="btn btn-outline-secondary">
            <i data-feather="arrow-left"></i> Kembali ke Detail
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
            <div class="d-flex align-items-center gap-3 mb-1">
                <div class="d-flex align-items-center justify-content-center rounded-circle"
                     style="width: 48px; height: 48px; background: linear-gradient(135deg, #f59e0b, #d97706); color: white; font-size: 1.1rem; font-weight: 700;">
                    {{ str_pad($pendaftaran->no_antrian, 3, '0', STR_PAD_LEFT) }}
                </div>
                <div>
                    <h4 class="mb-0 fw-bold" style="color: #0f172a;">Ubah Data Pendaftaran</h4>
                    <small class="text-muted">{{ $pendaftaran->nomor_pendaftaran }} — {{ $pendaftaran->pasien->nama_lengkap ?? '-' }}</small>
                </div>
            </div>
        </div>

        <div class="card-body px-4 mt-3">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('pendaftaran.update', $pendaftaran->id_pendaftaran) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Status Antrian --}}
                <div class="mb-4">
                    <h5 class="border-bottom pb-2 fw-bold" style="color: #334155;">
                        <i data-feather="activity" style="width:18px;height:18px;"></i> Status Antrian
                    </h5>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="status_pendaftaran" class="form-label fw-bold">Status Pendaftaran <span class="text-danger">*</span></label>
                            <select class="form-select" id="status_pendaftaran" name="status_pendaftaran" required>
                                <option value="baru" {{ old('status_pendaftaran', $pendaftaran->status_pendaftaran) == 'baru' ? 'selected' : '' }}>
                                    ⏳ Menunggu Panggilan
                                </option>
                                <option value="diproses" {{ old('status_pendaftaran', $pendaftaran->status_pendaftaran) == 'diproses' ? 'selected' : '' }}>
                                    🩺 Sedang Periksa
                                </option>
                                <option value="selesai" {{ old('status_pendaftaran', $pendaftaran->status_pendaftaran) == 'selesai' ? 'selected' : '' }}>
                                    ✅ Selesai
                                </option>
                                <option value="batal" {{ old('status_pendaftaran', $pendaftaran->status_pendaftaran) == 'batal' ? 'selected' : '' }}>
                                    ❌ Batal
                                </option>
                            </select>
                            <div class="form-text">Perbarui status sesuai kondisi antrian pasien saat ini.</div>
                        </div>
                    </div>
                </div>

                {{-- Pemeriksaan Fisik --}}
                <div class="mb-4">
                    <h5 class="border-bottom pb-2 fw-bold" style="color: #334155;">
                        <i data-feather="thermometer" style="width:18px;height:18px;"></i> Data Pemeriksaan Fisik Awal
                    </h5>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="suhu_tubuh" class="form-label">Suhu Tubuh (°C)</label>
                            <div class="input-group">
                                <input type="number" step="0.1" min="30" max="45"
                                       class="form-control" id="suhu_tubuh" name="suhu_tubuh"
                                       value="{{ old('suhu_tubuh', $pendaftaran->suhu_tubuh) }}"
                                       placeholder="Contoh: 36.5">
                                <span class="input-group-text">°C</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="berat_badan" class="form-label">Berat Badan (kg)</label>
                            <div class="input-group">
                                <input type="number" step="0.1" min="1" max="300"
                                       class="form-control" id="berat_badan" name="berat_badan"
                                       value="{{ old('berat_badan', $pendaftaran->berat_badan) }}"
                                       placeholder="Contoh: 65">
                                <span class="input-group-text">kg</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="tinggi_badan" class="form-label">Tinggi Badan (cm)</label>
                            <div class="input-group">
                                <input type="number" step="0.1" min="50" max="250"
                                       class="form-control" id="tinggi_badan" name="tinggi_badan"
                                       value="{{ old('tinggi_badan', $pendaftaran->tinggi_badan) }}"
                                       placeholder="Contoh: 170">
                                <span class="input-group-text">cm</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Info Pendaftaran (readonly) --}}
                <div class="mb-4">
                    <h5 class="border-bottom pb-2 fw-bold" style="color: #334155;">
                        <i data-feather="info" style="width:18px;height:18px;"></i> Informasi Pendaftaran
                    </h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="p-3 rounded" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                                <div class="row">
                                    <div class="col-5 text-muted fw-semibold" style="font-size:0.85rem;">Pasien</div>
                                    <div class="col-7 fw-bold" style="font-size:0.85rem; color:#0f172a;">{{ $pendaftaran->pasien->nama_lengkap ?? '-' }}</div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-5 text-muted fw-semibold" style="font-size:0.85rem;">Poliklinik</div>
                                    <div class="col-7 fw-bold" style="font-size:0.85rem; color:#0f172a;">{{ $pendaftaran->poli->nama_poli ?? '-' }}</div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-5 text-muted fw-semibold" style="font-size:0.85rem;">Dokter</div>
                                    <div class="col-7 fw-bold" style="font-size:0.85rem; color:#0f172a;">Dr. {{ $pendaftaran->dokter->nama_dokter ?? '-' }}</div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-5 text-muted fw-semibold" style="font-size:0.85rem;">Keluhan</div>
                                    <div class="col-7" style="font-size:0.85rem; color:#475569;">{{ $pendaftaran->keluhan ?? '-' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tombol Submit --}}
                <div class="mt-4 text-end border-top pt-4">
                    <a href="{{ route('pendaftaran.show', $pendaftaran->id_pendaftaran) }}" class="btn btn-light me-2">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-warning fw-bold px-4">
                        <i data-feather="save" style="width:16px;height:16px;" class="me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
