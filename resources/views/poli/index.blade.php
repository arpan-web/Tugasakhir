@extends('layouts.app')

@section('title', 'Data Poliklinik')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Data Poliklinik</h2>
        <a href="{{ route('poli.create') }}" class="btn btn-primary"><i data-feather="plus"></i> Tambah Poli</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-hover table-striped mt-3">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th width="30%">Nama Poliklinik</th>
                        <th width="45%">Keterangan</th>
                        <th width="20%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($polis as $key => $poli)
                    <tr>
                        <td>{{ $polis->firstItem() + $key }}</td>
                        <td class="fw-bold">{{ $poli->nama_poli }}</td>
                        <td>{{ $poli->keterangan ?? '-' }}</td>
                        <td>
                            <a href="{{ route('poli.edit', $poli->id_poli) }}" class="btn btn-sm btn-outline-warning" title="Edit"><i data-feather="edit-2"></i> Edit</a>
                            
                            <form action="{{ route('poli.destroy', $poli->id_poli) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus Poliklinik ini? Semua data dokter dan pendaftaran terkait akan ikut terhapus.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus"><i data-feather="trash-2"></i> Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">Belum ada data Poliklinik.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            
            <div class="d-flex justify-content-end mt-3">
                {{ $polis->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
