@extends('layouts.app')

@section('title', 'Tambah Poliklinik')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <a href="{{ route('poli.index') }}" class="btn btn-outline-secondary"><i data-feather="arrow-left"></i> Kembali</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 pt-4 pb-0">
            <h4 class="mb-0">Form Tambah Poliklinik</h4>
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

            <form action="{{ route('poli.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-12">
                        <label for="nama_poli" class="form-label fw-bold">Nama Poliklinik</label>
                        <input type="text" class="form-control" id="nama_poli" name="nama_poli" value="{{ old('nama_poli') }}" placeholder="Contoh: Poli Umum" required>
                    </div>

                    <div class="col-md-12">
                        <label for="keterangan" class="form-label fw-bold">Keterangan / Deskripsi</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="4" placeholder="Penjelasan singkat mengenai poliklinik... (opsional)">{{ old('keterangan') }}</textarea>
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary"><i data-feather="save" class="me-1"></i> Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
