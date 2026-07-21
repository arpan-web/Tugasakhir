@extends('layouts.app')

@section('title', 'Edit Obat')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <a href="{{ route('obat.index') }}" class="btn btn-outline-secondary"><i data-feather="arrow-left"></i> Kembali</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 pt-4 pb-0">
            <h4 class="mb-0">Form Edit Obat</h4>
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

            <form action="{{ route('obat.update', $obat->id_obat) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6 mb-3">
                        <label for="kode_obat" class="form-label fw-bold">Kode Obat</label>
                        <input type="text" class="form-control text-uppercase" id="kode_obat" name="kode_obat" value="{{ old('kode_obat', $obat->kode_obat) }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="nama_obat" class="form-label fw-bold">Nama Obat / Barang</label>
                        <input type="text" class="form-control" id="nama_obat" name="nama_obat" value="{{ old('nama_obat', $obat->nama_obat) }}" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="satuan" class="form-label fw-bold">Satuan</label>
                        <select class="form-select" id="satuan" name="satuan" required>
                            <option value="tablet" {{ old('satuan', $obat->satuan) == 'tablet' ? 'selected' : '' }}>Tablet</option>
                            <option value="kapsul" {{ old('satuan', $obat->satuan) == 'kapsul' ? 'selected' : '' }}>Kapsul</option>
                            <option value="botol" {{ old('satuan', $obat->satuan) == 'botol' ? 'selected' : '' }}>Botol</option>
                            <option value="sachet" {{ old('satuan', $obat->satuan) == 'sachet' ? 'selected' : '' }}>Sachet</option>
                            <option value="sirup" {{ old('satuan', $obat->satuan) == 'sirup' ? 'selected' : '' }}>Sirup</option>
                            <option value="salep" {{ old('satuan', $obat->satuan) == 'salep' ? 'selected' : '' }}>Salep</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="stok_tersedia" class="form-label fw-bold">Stok Tersedia</label>
                        <input type="number" min="0" class="form-control" id="stok_tersedia" name="stok_tersedia" value="{{ old('stok_tersedia', $obat->stok_tersedia) }}" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="stok_minimal" class="form-label fw-bold">Batas Stok Minimal</label>
                        <input type="number" min="0" class="form-control" id="stok_minimal" name="stok_minimal" value="{{ old('stok_minimal', $obat->stok_minimal) }}" required>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="keterangan" class="form-label">Keterangan / Deskripsi</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="3">{{ old('keterangan', $obat->keterangan) }}</textarea>
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary"><i data-feather="save" class="me-1"></i> Perbarui Data Obat</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
