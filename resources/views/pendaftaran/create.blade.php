@extends('layouts.app')

@section('title', 'Tambah Pendaftaran Pasien')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <a href="{{ route('pendaftaran.index') }}" class="btn btn-outline-secondary"><i data-feather="arrow-left"></i> Kembali</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 pt-4 pb-0">
            <h4 class="mb-0">Form Pendaftaran Antrian</h4>
        </div>
        <div class="card-body mt-3">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('pendaftaran.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    
                    <div class="col-md-6 mb-3">
                        <label for="id_pasien" class="form-label fw-bold">Pilih Pasien</label>
                        <select class="form-select" id="id_pasien" name="id_pasien" required>
                            <option value="">Cari dan Pilih Pasien...</option>
                            @foreach($pasiens as $pasien)
                                <option value="{{ $pasien->id_pasien }}" {{ (old('id_pasien') == $pasien->id_pasien || request('id_pasien') == $pasien->id_pasien) ? 'selected' : '' }}>
                                    {{ $pasien->nomor_pasien }} - {{ $pasien->nama_lengkap }} ({{ $pasien->status_pasien }})
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text">Pasien belum terdaftar? <a href="{{ route('pasien.create') }}">Tambah Pasien Baru</a></div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="id_poli" class="form-label fw-bold">Poliklinik Tujuan</label>
                        <select class="form-select" id="id_poli" name="id_poli" required>
                            <option value="">Pilih Poli...</option>
                            @foreach($polis as $poli)
                                <option value="{{ $poli->id_poli }}" {{ old('id_poli') == $poli->id_poli ? 'selected' : '' }}>
                                    {{ $poli->nama_poli }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="id_dokter" class="form-label fw-bold">Dokter Jaga</label>
                        <select class="form-select" id="id_dokter" name="id_dokter" required>
                            <option value="">Pilih Dokter...</option>
                            @foreach($dokters as $dokter)
                                <option value="{{ $dokter->id_dokter }}" {{ old('id_dokter') == $dokter->id_dokter ? 'selected' : '' }}>
                                    Dr. {{ $dokter->nama_dokter }} ({{ $dokter->poli->nama_poli ?? 'Umum' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Pemeriksaan Fisik Awal oleh Perawat -->
                    <div class="col-12 mt-4 mb-2">
                        <h5 class="border-bottom pb-2">Pemeriksaan Awal (Opsional)</h5>
                    </div>

                    <div class="col-md-4">
                        <label for="suhu_tubuh" class="form-label">Suhu Tubuh (°C)</label>
                        <input type="number" step="0.1" class="form-control" id="suhu_tubuh" name="suhu_tubuh" value="{{ old('suhu_tubuh') }}" placeholder="Contoh: 36.5">
                    </div>

                    <div class="col-md-4">
                        <label for="berat_badan" class="form-label">Berat Badan (kg)</label>
                        <input type="number" step="0.1" class="form-control" id="berat_badan" name="berat_badan" value="{{ old('berat_badan') }}" placeholder="Contoh: 65">
                    </div>

                    <div class="col-md-4">
                        <label for="tinggi_badan" class="form-label">Tinggi Badan (cm)</label>
                        <input type="number" step="0.1" class="form-control" id="tinggi_badan" name="tinggi_badan" value="{{ old('tinggi_badan') }}" placeholder="Contoh: 170">
                    </div>

                    <!-- Keluhan -->
                    <div class="col-12 mt-3">
                        <label for="keluhan" class="form-label fw-bold">Keluhan Pasien</label>
                        <textarea class="form-control" id="keluhan" name="keluhan" rows="3" required placeholder="Jelaskan keluhan utama pasien sakit apa...">{{ old('keluhan') }}</textarea>
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <button type="reset" class="btn btn-light me-2">Reset Form</button>
                    <button type="submit" class="btn btn-primary"><i data-feather="check-circle" class="me-1"></i> Daftarkan Antrian</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
