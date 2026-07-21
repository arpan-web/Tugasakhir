@extends('layouts.app')

@section('title', 'Antrian Pemeriksaan Pasien')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Daftar Antrian Pemeriksaan</h2>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('warning'))
                <div class="alert alert-warning">{{ session('warning') }}</div>
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
                            <th>Tgl & Waktu Masuk</th>
                            <th>No. Registrasi</th>
                            <th>Nama Pasien</th>
                            <th>Poliklinik Tujuan</th>
                            <th>Dokter Jaga</th>
                            <th>Keluhan Awal</th>
                            <th>Status</th>
                            <th>Aksi Pemeriksaan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendaftarans as $pendaftaran)
                        <tr>
                            <td>{{ $pendaftaran->created_at->format('d/m/Y H:i') }}</td>
                            <td><code>{{ $pendaftaran->nomor_pendaftaran }}</code></td>
                            <td class="fw-bold">{{ $pendaftaran->pasien->nama_lengkap ?? '-' }}</td>
                            <td><span class="badge bg-info text-dark">{{ $pendaftaran->poli->nama_poli ?? '-' }}</span></td>
                            <td>{{ $pendaftaran->dokter->nama_dokter ?? '-' }}</td>
                            <td>{{ Str::limit($pendaftaran->keluhan, 30) }}</td>
                            <td>
                                @if($pendaftaran->status_pendaftaran == 'baru')
                                    <span class="badge bg-danger">Menunggu</span>
                                @elseif($pendaftaran->status_pendaftaran == 'diproses')
                                    <span class="badge bg-warning text-dark">Sedang Diperiksa</span>
                                @elseif($pendaftaran->status_pendaftaran == 'selesai')
                                    <span class="badge bg-success">Selesai Berobat</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($pendaftaran->status_pendaftaran) }}</span>
                                @endif
                            </td>
                            <td>
                                @if(in_array($pendaftaran->status_pendaftaran, ['baru', 'diproses']))
                                    <a href="{{ route('diagnosa.create', ['pendaftaran' => $pendaftaran->id_pendaftaran]) }}" class="btn btn-sm btn-primary">
                                        <i data-feather="stethoscope"></i> Proses Periksa
                                    </a>
                                @elseif($pendaftaran->status_pendaftaran == 'selesai' && $pendaftaran->diagnosa)
                                    <a href="{{ route('diagnosa.show', $pendaftaran->diagnosa->id_diagnosa) }}" class="btn btn-sm btn-outline-info">
                                        <i data-feather="eye"></i> Lihat Hasil
                                    </a>
                                    <form action="{{ route('diagnosa.destroy', $pendaftaran->diagnosa->id_diagnosa) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin membatalkan diagnosa? Aksi ini akan mengembalikan status pasien menjadi Diproses dan merestore stok obat.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Batal Diagnosa"><i data-feather="x"></i> Batal</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">Belum ada pasien dalam antrian pemeriksaan poli Anda.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-end mt-3">
                {{ $pendaftarans->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
