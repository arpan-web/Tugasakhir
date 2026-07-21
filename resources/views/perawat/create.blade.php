@extends('layouts.app')

@section('title', 'Tambah Perawat')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <a href="{{ route('perawat.index') }}" class="btn btn-outline-secondary"><i data-feather="arrow-left"></i> Kembali</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 pt-4 pb-0">
            <h4 class="mb-0">Form Tambah Perawat</h4>
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

            <form action="{{ route('perawat.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6 mb-3">
                        <label for="nama_perawat" class="form-label fw-bold">Nama Perawat</label>
                        <input type="text" class="form-control" id="nama_perawat" name="nama_perawat" value="{{ old('nama_perawat') }}" placeholder="Tanpa gelar Ns. (Contoh: Siti Aminah)" required>
                        <div class="form-text">Gelar 'Ns.' akan ditambahkan secara otomatis pada nama akun login.</div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="no_hp" class="form-label">Nomor Handphone</label>
                        <input type="text" class="form-control" id="no_hp" name="no_hp" value="{{ old('no_hp') }}" placeholder="Contoh: 08123456789 (opsional)">
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary"><i data-feather="save" class="me-1"></i> Simpan Data Perawat</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
