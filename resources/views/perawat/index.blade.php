@extends('layouts.app')

@section('title', 'Data Perawat')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Data Perawat</h2>
        <a href="{{ route('perawat.create') }}" class="btn btn-primary"><i data-feather="plus"></i> Tambah Perawat</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="table-responsive mt-3">
                <table class="table table-hover table-striped">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama Perawat</th>
                            <th>No. HP</th>
                            <th>Username Akun</th>
                            <th width="20%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($perawats as $key => $perawat)
                        <tr>
                            <td>{{ $perawats->firstItem() + $key }}</td>
                            <td class="fw-bold">{{ $perawat->nama_perawat }}</td>
                            <td>{{ $perawat->no_hp ?? '-' }}</td>
                            <td><code>{{ $perawat->user->username ?? '-' }}</code></td>
                            <td>
                                <a href="{{ route('perawat.edit', $perawat->id_perawat) }}" class="btn btn-sm btn-outline-warning" title="Edit"><i data-feather="edit-2"></i></a>
                                
                                <form action="{{ route('perawat.destroy', $perawat->id_perawat) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin menghapus perawat ini? Akun login perawat ini juga akan ikut terhapus permanen.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus"><i data-feather="trash-2"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Belum ada data Perawat.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-end mt-3">
                {{ $perawats->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
