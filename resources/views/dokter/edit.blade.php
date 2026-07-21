@extends('layouts.app')

@section('title', 'Edit Dokter')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <a href="{{ route('dokter.index') }}" class="btn btn-outline-secondary"><i data-feather="arrow-left"></i> Kembali</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 pt-4 pb-0">
            <h4 class="mb-0">Form Edit Dokter</h4>
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

            <form action="{{ route('dokter.update', $dokter->id_dokter) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-12 mb-2">
                        <div class="alert alert-info py-2 mb-0 d-flex justify-content-between align-items-center">
                            <div>
                                <strong>ID Dokter:</strong> {{ $dokter->id_dokter }}<br>
                                <strong>Username Login:</strong> {{ $dokter->user->username ?? 'Tidak tersedia' }}
                            </div>
                            <div>
                                <span class="badge bg-secondary">Password login tdk dpt diubah disini</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="nama_dokter" class="form-label fw-bold">Nama Dokter</label>
                        <input type="text" class="form-control" id="nama_dokter" name="nama_dokter" value="{{ old('nama_dokter', $dokter->nama_dokter) }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="id_poli" class="form-label fw-bold">Poliklinik Penempatan</label>
                        <select class="form-select" id="id_poli" name="id_poli" required>
                            <option value="">Pilih Poli...</option>
                            @foreach($polis as $poli)
                                <option value="{{ $poli->id_poli }}" {{ old('id_poli', $dokter->id_poli) == $poli->id_poli ? 'selected' : '' }}>
                                    {{ $poli->nama_poli }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="spesialisasi" class="form-label">Spesialisasi</label>
                        <input type="text" class="form-control" id="spesialisasi" name="spesialisasi" value="{{ old('spesialisasi', $dokter->spesialisasi) }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="no_hp" class="form-label">Nomor Handphone</label>
                        <input type="text" class="form-control" id="no_hp" name="no_hp" value="{{ old('no_hp', $dokter->no_hp) }}">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="jadwal_praktek" class="form-label">Jadwal Praktek</label>
                        <input type="text" class="form-control" id="jadwal_praktek" name="jadwal_praktek" value="{{ old('jadwal_praktek', $dokter->jadwal_praktek) }}">
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary"><i data-feather="save" class="me-1"></i> Perbarui Data Dokter</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
