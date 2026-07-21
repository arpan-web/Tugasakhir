@extends('layouts.app')

@section('title', 'Edit Perawat')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <a href="{{ route('perawat.index') }}" class="btn btn-outline-secondary"><i data-feather="arrow-left"></i> Kembali</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 pt-4 pb-0">
            <h4 class="mb-0">Form Edit Perawat</h4>
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

            <form action="{{ route('perawat.update', $perawat->id_perawat) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-12 mb-2">
                        <div class="alert alert-info py-2 mb-0 d-flex justify-content-between align-items-center">
                            <div>
                                <strong>ID Perawat:</strong> {{ $perawat->id_perawat }}<br>
                                <strong>Username Login:</strong> {{ $perawat->user->username ?? 'Tidak tersedia' }}
                            </div>
                            <div>
                                <span class="badge bg-secondary">Password login tdk dpt diubah disini</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="nama_perawat" class="form-label fw-bold">Nama Perawat</label>
                        <input type="text" class="form-control" id="nama_perawat" name="nama_perawat" value="{{ old('nama_perawat', $perawat->nama_perawat) }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="no_hp" class="form-label">Nomor Handphone</label>
                        <input type="text" class="form-control" id="no_hp" name="no_hp" value="{{ old('no_hp', $perawat->no_hp) }}">
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary"><i data-feather="save" class="me-1"></i> Perbarui Data Perawat</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
