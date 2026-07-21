@extends('layouts.app')

@section('title', 'Tambah Obat')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <a href="{{ route('obat.index') }}" class="btn btn-outline-secondary"><i data-feather="arrow-left"></i> Kembali</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 pt-4 pb-0">
            <h4 class="mb-0">Form Tambah Obat</h4>
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

            <form action="{{ route('obat.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6 mb-3">
                        <label for="kode_obat" class="form-label fw-bold">Kode Obat</label>
                        <input type="text" class="form-control text-uppercase" id="kode_obat" name="kode_obat" value="{{ old('kode_obat') }}" placeholder="Contoh: OBT-001" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="nama_obat" class="form-label fw-bold">Nama Obat / Barang</label>
                        <input type="text" class="form-control" id="nama_obat" name="nama_obat" value="{{ old('nama_obat') }}" placeholder="Contoh: Paracetamol 500mg" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="satuan" class="form-label fw-bold">Satuan</label>
                        <select class="form-select" id="satuan" name="satuan" required>
                            <option value="">Pilih Satuan...</option>
                            <option value="tablet" {{ old('satuan') == 'tablet' ? 'selected' : '' }}>Tablet</option>
                            <option value="kapsul" {{ old('satuan') == 'kapsul' ? 'selected' : '' }}>Kapsul</option>
                            <option value="botol" {{ old('satuan') == 'botol' ? 'selected' : '' }}>Botol</option>
                            <option value="sachet" {{ old('satuan') == 'sachet' ? 'selected' : '' }}>Sachet</option>
                            <option value="sirup" {{ old('satuan') == 'sirup' ? 'selected' : '' }}>Sirup</option>
                            <option value="salep" {{ old('satuan') == 'salep' ? 'selected' : '' }}>Salep</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="stok_tersedia" class="form-label fw-bold">Stok Awal</label>
                        <input type="number" min="0" class="form-control" id="stok_tersedia" name="stok_tersedia" value="{{ old('stok_tersedia', 0) }}" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="stok_minimal" class="form-label fw-bold">Batas Stok Minimal (Peringatan)</label>
                        <input type="number" min="0" class="form-control" id="stok_minimal" name="stok_minimal" value="{{ old('stok_minimal', 10) }}" required>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="keterangan" class="form-label">Keterangan / Deskripsi</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="3" placeholder="Informasi tambahan mengenai obat ini...">{{ old('keterangan') }}</textarea>
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary"><i data-feather="save" class="me-1"></i> Simpan Data Obat</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
