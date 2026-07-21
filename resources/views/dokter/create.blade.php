@extends('layouts.app')

@section('title', 'Tambah Dokter')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <a href="{{ route('dokter.index') }}" class="btn btn-outline-secondary"><i data-feather="arrow-left"></i> Kembali</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 pt-4 pb-0">
            <h4 class="mb-0">Form Tambah Dokter</h4>
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

            <form action="{{ route('dokter.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6 mb-3">
                        <label for="nama_dokter" class="form-label fw-bold">Nama Dokter</label>
                        <input type="text" class="form-control" id="nama_dokter" name="nama_dokter" value="{{ old('nama_dokter') }}" placeholder="Tanpa gelar Dr. (Contoh: Budi Susanto)" required>
                        <div class="form-text">Gelar 'Dr.' akan ditambahkan secara otomatis pada nama akun login.</div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="id_poli" class="form-label fw-bold">Poliklinik Penempatan</label>
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
                        <label for="spesialisasi" class="form-label">Spesialisasi</label>
                        <input type="text" class="form-control" id="spesialisasi" name="spesialisasi" value="{{ old('spesialisasi') }}" placeholder="Contoh: Spesialis Penyakit Dalam (opsional)">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="no_hp" class="form-label">Nomor Handphone</label>
                        <input type="text" class="form-control" id="no_hp" name="no_hp" value="{{ old('no_hp') }}" placeholder="Contoh: 08123456789 (opsional)">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="jadwal_praktek" class="form-label">Jadwal Praktek</label>
                        <input type="text" class="form-control" id="jadwal_praktek" name="jadwal_praktek" value="{{ old('jadwal_praktek') }}" placeholder="Contoh: Senin - Jumat, 08:00 - 14:00 (opsional)">
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary"><i data-feather="save" class="me-1"></i> Simpan Data Dokter</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
