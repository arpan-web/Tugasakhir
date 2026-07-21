@extends('layouts.app')

@section('title', 'Transaksi Stok Obat')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Riwayat Transaksi Stok Obat</h2>
        <a href="{{ route('stok_transaksi.create') }}" class="btn btn-primary"><i data-feather="plus"></i> Input Transaksi Baru</a>
    </div>

    <!-- Alert Stok Kritis Global Module -->
    @php
        $stokKritisCount = \App\Models\Obat::whereRaw('stok_tersedia <= stok_minimal')->count();
    @endphp
    @if($stokKritisCount > 0)
        <div class="alert alert-warning mb-4">
            <i data-feather="alert-triangle" class="me-2"></i> Terdapat <strong>{{ $stokKritisCount }} jenis obat</strong> yang stoknya sudah mencapai batas menipis atau habis. <a href="{{ route('obat.index') }}" class="alert-link">Cek Master Obat</a>
        </div>
    @endif

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
                            <th>Waktu Transaksi</th>
                            <th>Nama Obat</th>
                            <th>Jns Transaksi</th>
                            <th>Jmlh</th>
                            <th>Oleh</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksis as $trx)
                        <tr>
                            <td>{{ date('d/m/Y H:i', strtotime($trx->tanggal_transaksi)) }}</td>
                            <td class="fw-bold">{{ $trx->obat->nama_obat ?? 'Obat Tdk Diketahui' }}</td>
                            <td>
                                @if($trx->jenis_transaksi == 'masuk')
                                    <span class="badge bg-success"><i data-feather="arrow-down-left" class="icon-sm"></i> Masuk</span>
                                @else
                                    <span class="badge bg-danger"><i data-feather="arrow-up-right" class="icon-sm"></i> Keluar</span>
                                @endif
                            </td>
                            <td class="fw-bold">{{ $trx->jumlah }} {{ $trx->obat->satuan ?? '' }}</td>
                            <td>{{ $trx->user->username ?? 'Sistem' }}</td>
                            <td>{{ Str::limit($trx->keterangan, 30) ?: '-' }}</td>
                            <td>
                                <form action="{{ route('stok_transaksi.destroy', $trx->id_stok_transaksi) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin membatalkan riwayat transaksi {{ $trx->jenis_transaksi }} ini? Stok akan otomatis direstore (bertambah/berkurang sesuai pemulihan).')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Batalkan/Hapus Transaksi"><i data-feather="rotate-ccw"></i> Batal</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Belum ada riwayat transaksi logistik apotek.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-end mt-3">
                {{ $transaksis->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
<style>
    .icon-sm { width: 14px; height: 14px; }
</style>
@endsection
