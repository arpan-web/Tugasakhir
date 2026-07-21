@extends('layouts.app')

@section('title', 'Data Dokter')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Data Dokter</h2>
        <a href="{{ route('dokter.create') }}" class="btn btn-primary"><i data-feather="plus"></i> Tambah Dokter</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="table-responsive mt-3">
                <table class="table table-hover table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Dokter</th>
                            <th>Poliklinik</th>
                            <th>Spesialisasi</th>
                            <th>No. HP</th>
                            <th>Jadwal Praktek</th>
                            <th>Username Akun</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dokters as $key => $dokter)
                        <tr>
                            <td>{{ $dokters->firstItem() + $key }}</td>
                            <td class="fw-bold">{{ $dokter->nama_dokter }}</td>
                            <td><span class="badge bg-info text-dark">{{ $dokter->poli->nama_poli ?? '-' }}</span></td>
                            <td>{{ $dokter->spesialisasi ?? '-' }}</td>
                            <td>{{ $dokter->no_hp ?? '-' }}</td>
                            <td>{{ $dokter->jadwal_praktek ?? '-' }}</td>
                            <td><code>{{ $dokter->user->username ?? '-' }}</code></td>
                            <td>
                                <a href="{{ route('dokter.edit', $dokter->id_dokter) }}" class="btn btn-sm btn-outline-warning" title="Edit"><i data-feather="edit-2"></i></a>
                                
                                <form action="{{ route('dokter.destroy', $dokter->id_dokter) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin menghapus dokter ini? Akun login dari dokter ini juga akan ikut terhapus permanen.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus"><i data-feather="trash-2"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">Belum ada data Dokter.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-end mt-3">
                {{ $dokters->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
