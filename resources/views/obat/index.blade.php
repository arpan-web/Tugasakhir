@extends('layouts.app')

@section('title', 'Data Obat/Barang Farmasi')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Data Obat & Barang Farmasi</h2>
        <a href="{{ route('obat.create') }}" class="btn btn-primary"><i data-feather="plus"></i> Tambah Obat</a>
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
                            <th>Kode Obat</th>
                            <th>Nama Obat</th>
                            <th>Satuan</th>
                            <th>Stok Tersedia</th>
                            <th>Batas Minimal</th>
                            <th>Status Stok</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($obats as $obat)
                        <tr>
                            <td><code>{{ $obat->kode_obat }}</code></td>
                            <td class="fw-bold">{{ $obat->nama_obat }}</td>
                            <td>{{ ucfirst($obat->satuan) }}</td>
                            <td class="text-end fw-bold">{{ $obat->stok_tersedia }}</td>
                            <td class="text-end">{{ $obat->stok_minimal }}</td>
                            <td>
                                @if($obat->stok_tersedia <= 0)
                                    <span class="badge bg-danger">Habis</span>
                                @elseif($obat->stok_tersedia <= $obat->stok_minimal)
                                    <span class="badge bg-warning text-dark">Kritis / Hampir Habis</span>
                                @else
                                    <span class="badge bg-success">Aman</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('obat.edit', $obat->id_obat) }}" class="btn btn-sm btn-outline-warning" title="Edit"><i data-feather="edit-2"></i></a>
                                
                                <form action="{{ route('obat.destroy', $obat->id_obat) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin menghapus obat ini? Jika sudah pernah ditransaksikan, sebaiknya JANGAN dihapus.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus"><i data-feather="trash-2"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Belum ada data Obat di Master Data.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-end mt-3">
                {{ $obats->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
