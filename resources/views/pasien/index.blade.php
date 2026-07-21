@extends('layouts.app')

@section('title', 'Data Pasien')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Data Pasien</h2>
        <a href="{{ route('pasien.create') }}" class="btn btn-primary"><i data-feather="plus"></i> Tambah Pasien</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-hover table-striped mt-3">
                <thead class="table-light">
                    <tr>
                        <th>No. Rekam Medis</th>
                        <th>Nama Lengkap</th>
                        <th>Jenis Kelamin</th>
                        <th>Status</th>
                        <th>No. HP</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pasiens as $pasien)
                    <tr>
                        <td><span class="badge bg-secondary">{{ $pasien->nomor_pasien }}</span></td>
                        <td>{{ $pasien->nama_lengkap }}</td>
                        <td>{{ $pasien->jenis_kelamin == 'L' ? 'Laki-Laki' : 'Perempuan' }}</td>
                        <td>
                            <span class="badge {{ $pasien->status_pasien == 'Polnep' ? 'bg-primary' : 'bg-info' }}">
                                {{ $pasien->status_pasien }}
                            </span>
                            @if($pasien->nomor_kartu_pasien)
                                <br><small class="text-muted">{{ $pasien->nomor_kartu_pasien }}</small>
                            @endif
                        </td>
                        <td>{{ $pasien->no_hp }}</td>
                        <td>
                            <a href="{{ route('pendaftaran.create', ['id_pasien' => $pasien->id_pasien]) }}" class="btn btn-sm btn-outline-primary" title="Daftarkan Antrian"><i data-feather="clipboard"></i> Antrean</a>
                            <a href="{{ route('pasien.edit', $pasien->id_pasien) }}" class="btn btn-sm btn-outline-warning"><i data-feather="edit-2"></i> Edit</a>
                            <form action="{{ route('pasien.destroy', $pasien->id_pasien) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus pasien ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"><i data-feather="trash-2"></i> Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">Belum ada data pasien.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            
            <div class="d-flex justify-content-end mt-3">
                {{ $pasiens->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
