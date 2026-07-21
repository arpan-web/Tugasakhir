@extends('layouts.app')

@section('title', 'Data Pendaftaran / Antrian')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Antrian Pendaftaran</h2>
        @if(in_array(auth()->user()->role, ['admin', 'perawat']))
        <a href="{{ route('pendaftaran.create') }}" class="btn btn-primary"><i data-feather="plus"></i> Tambah Pendaftaran</a>
        @endif
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-hover table-striped mt-3">
                <thead class="table-light">
                    <tr>
                        <th>Tgl Daftar</th>
                        <th>No Pendaftaran</th>
                        <th>No Antrian</th>
                        <th>Nama Pasien</th>
                        <th>Poli Tujuan</th>
                        <th>Dokter</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendaftarans as $pendaftar)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($pendaftar->tanggal_daftar)->format('d-m-Y') }}</td>
                        <td><small class="text-muted">{{ $pendaftar->nomor_pendaftaran }}</small></td>
                        <td>
                            <span class="badge bg-primary px-3 py-2 rounded-pill fs-6">{{ str_pad($pendaftar->no_antrian, 3, '0', STR_PAD_LEFT) }}</span>
                        </td>
                        <td>{{ $pendaftar->pasien->nama_lengkap ?? '-' }}</td>
                        <td>{{ $pendaftar->poli->nama_poli ?? '-' }}</td>
                        <td>{{ $pendaftar->dokter->nama_dokter ?? '-' }}</td>
                        <td>
                            @if($pendaftar->status_pendaftaran == 'baru')
                                <span class="badge bg-info text-dark">Menunggu Panggilan</span>
                            @elseif($pendaftar->status_pendaftaran == 'diproses')
                                <span class="badge bg-warning text-dark">Sedang Periksa</span>
                            @elseif($pendaftar->status_pendaftaran == 'selesai')
                                <span class="badge bg-success">Selesai</span>
                            @else
                                <span class="badge bg-danger">Batal</span>
                            @endif
                        </td>
                        <td>
                            <!-- Tombol ini juga berguna nanti untuk modul Diagnosa Dokter -->
                            <a href="{{ route('pendaftaran.show', $pendaftar->id_pendaftaran) }}" class="btn btn-sm btn-outline-primary" title="Detail"><i data-feather="eye"></i></a>
                            
                            @if(in_array(auth()->user()->role, ['admin', 'perawat']))
                            <a href="{{ route('pendaftaran.edit', $pendaftar->id_pendaftaran) }}" class="btn btn-sm btn-outline-warning" title="Ubah Status Fisik"><i data-feather="edit-2"></i></a>
                            
                            <form action="{{ route('pendaftaran.destroy', $pendaftar->id_pendaftaran) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin membatalkan/menghapus pendaftaran ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus"><i data-feather="trash-2"></i></button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">Belum ada data pendaftaran di sistem.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            
            <div class="d-flex justify-content-end mt-3">
                {{ $pendaftarans->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
