@extends('layouts.app')

@section('title', 'Edit Data Pasien')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <a href="{{ route('pasien.index') }}" class="btn btn-outline-secondary"><i data-feather="arrow-left"></i> Kembali</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 pt-4 pb-0">
            <h4 class="mb-0">Form Edit Pasien</h4>
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

            <form action="{{ route('pasien.update', $pasien->id_pasien) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <!-- Nomor Rekam Medis (Readonly) -->
                    <div class="col-md-12 mb-2">
                         <div class="alert alert-info py-2 mb-0">
                             <strong>Nomor Rekam Medis:</strong> {{ $pasien->nomor_pasien }}
                         </div>
                    </div>

                    <!-- Nama Lengkap -->
                    <div class="col-md-6">
                        <label for="nama_lengkap" class="form-label fw-bold">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap', $pasien->nama_lengkap) }}" required>
                    </div>

                    <!-- Jenis Kelamin -->
                    <div class="col-md-6">
                        <label for="jenis_kelamin" class="form-label fw-bold">Jenis Kelamin</label>
                        <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                            <option value="">Pilih...</option>
                            <option value="L" {{ old('jenis_kelamin', $pasien->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-Laki</option>
                            <option value="P" {{ old('jenis_kelamin', $pasien->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <!-- Tanggal Lahir -->
                    <div class="col-md-6">
                        <label for="tanggal_lahir" class="form-label fw-bold">Tanggal Lahir</label>
                        <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir', $pasien->tanggal_lahir) }}" required>
                    </div>

                    <!-- No. HP -->
                    <div class="col-md-6">
                        <label for="no_hp" class="form-label fw-bold">No. Handphone</label>
                        <input type="text" class="form-control" id="no_hp" name="no_hp" value="{{ old('no_hp', $pasien->no_hp) }}" required>
                    </div>

                    <!-- Status Pasien -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Status Kepesertaan/Pasien</label>
                        <div class="d-flex mt-2">
                            <div class="form-check me-4">
                                <input class="form-check-input" type="radio" name="status_pasien" id="statusPolnep" value="Polnep" {{ old('status_pasien', $pasien->status_pasien) == 'Polnep' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="statusPolnep">Sivitas Polnep</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status_pasien" id="statusUmum" value="Umum" {{ old('status_pasien', $pasien->status_pasien) == 'Umum' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="statusUmum">Umum</label>
                            </div>
                        </div>
                    </div>

                    <!-- Nomor Kartu -->
                    <div class="col-md-6">
                        <label for="nomor_kartu_pasien" class="form-label fw-bold">Nomor Kartu (Jika ada)</label>
                        <input type="text" class="form-control" id="nomor_kartu_pasien" name="nomor_kartu_pasien" value="{{ old('nomor_kartu_pasien', $pasien->nomor_kartu_pasien) }}">
                    </div>

                    <!-- Alamat -->
                    <div class="col-12">
                        <label for="alamat" class="form-label fw-bold">Alamat Lengkap</label>
                        <textarea class="form-control" id="alamat" name="alamat" rows="3" required>{{ old('alamat', $pasien->alamat) }}</textarea>
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <button type="reset" class="btn btn-light me-2">Batal</button>
                    <button type="submit" class="btn btn-primary"><i data-feather="save" class="me-1"></i> Perbarui Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
