@extends('layouts.app')

@section('title', 'Transaksi Stok Obat')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Riwayat Transaksi Stok Obat</h2>
            <a href="{{ route('stok_transaksi.create') }}" class="btn btn-primary"><i data-feather="plus"></i> Input
                Transaksi Baru</a>
        </div>

        <!-- Alert Stok Kritis & Kadaluarsa Global Module -->
        @php
            $stokKritisCount = \App\Models\Obat::whereRaw('stok_tersedia <= stok_minimal')->count();
            $stokExpiredList = \App\Models\StokTransaksi::with('obat')
                ->where('jenis_transaksi', 'masuk')
                ->where('sisa_stok', '>', 0)
                ->whereNotNull('tanggal_kadaluarsa')
                ->whereDate('tanggal_kadaluarsa', '<=', \Carbon\Carbon::today()->addDays(30))
                ->orderBy('tanggal_kadaluarsa', 'asc')
                ->get();
        @endphp

        @if(count($stokExpiredList) > 0)
            <div class="alert alert-danger mb-3 border-0 shadow-sm" style="border-left: 4px solid #ef4444 !important; background-color: rgba(239, 68, 68, 0.05);">
                <i data-feather="calendar" class="me-2 text-danger"></i> Terdapat <strong>{{ count($stokExpiredList) }} batch obat</strong> yang mendekati atau sudah kadaluarsa. Periksa kolom <strong>Sisa Batch & Expired</strong> di bawah untuk prioritas/pemusnahan.
            </div>
        @endif

        @if($stokKritisCount > 0)
            <div class="alert alert-warning mb-4 border-0 shadow-sm" style="border-left: 4px solid #f59e0b !important; background-color: rgba(245, 158, 11, 0.05);">
                <i data-feather="alert-triangle" class="me-2 text-warning"></i> Terdapat <strong>{{ $stokKritisCount }} jenis obat</strong> yang stoknya sudah mencapai batas menipis atau habis. <a href="{{ route('obat.index') }}" class="alert-link">Cek Master Obat</a>
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
                                <th>Sisa Batch & Expired</th>
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
                                            <span class="badge bg-success"><i data-feather="arrow-down-left" class="icon-sm"></i>
                                                Masuk</span>
                                        @else
                                            <span class="badge bg-danger"><i data-feather="arrow-up-right" class="icon-sm"></i>
                                                Keluar</span>
                                        @endif
                                    </td>
                                    <td class="fw-bold">{{ $trx->jumlah }} {{ $trx->obat->satuan ?? '' }}</td>
                                    <td>
                                        @if($trx->jenis_transaksi == 'masuk')
                                            <span class="badge bg-secondary" title="Sisa stok batch ini">Sisa: {{ $trx->sisa_stok }} / {{ $trx->jumlah }}</span>
                                            @if($trx->tanggal_kadaluarsa)
                                                @php $hariSisa = \Carbon\Carbon::today()->diffInDays($trx->tanggal_kadaluarsa, false); @endphp
                                                @if($hariSisa < 0 && $trx->sisa_stok > 0)
                                                    <span class="badge bg-danger mt-1 d-block">🚫 Expired ({{ date('d/m/Y', strtotime($trx->tanggal_kadaluarsa)) }})</span>
                                                @elseif($hariSisa <= 30 && $trx->sisa_stok > 0)
                                                    <span class="badge bg-warning text-dark mt-1 d-block">⚠ Expired {{ date('d/m/Y', strtotime($trx->tanggal_kadaluarsa)) }} ({{ $hariSisa }} hr)</span>
                                                @else
                                                    <span class="text-muted d-block small mt-1">Exp: {{ date('d/m/Y', strtotime($trx->tanggal_kadaluarsa)) }}</span>
                                                @endif
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $trx->user->username ?? 'Sistem' }}</td>
                                    <td>{{ Str::limit($trx->keterangan, 30) ?: '-' }}</td>
                                    <td>
                                        @if($trx->jenis_transaksi == 'masuk' && $trx->tanggal_kadaluarsa && \Carbon\Carbon::today()->diffInDays($trx->tanggal_kadaluarsa, false) < 0 && $trx->sisa_stok > 0)
                                            <form action="{{ route('stok_transaksi.musnahkan', $trx->id_stok_transaksi) }}" method="POST" class="d-inline me-1" onsubmit="return confirm('Musnahkan sisa {{ $trx->sisa_stok }} {{ $trx->obat->satuan ?? 'stok' }} obat kadaluarsa ini?')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-danger" title="Musnahkan Stok Kadaluarsa"><i data-feather="trash-2" class="icon-sm"></i> Musnahkan</button>
                                            </form>
                                        @endif
                                        <form action="{{ route('stok_transaksi.destroy', $trx->id_stok_transaksi) }}"
                                            method="POST" class="d-inline"
                                            onsubmit="return confirm('Yakin membatalkan riwayat transaksi {{ $trx->jenis_transaksi }} ini? Stok akan otomatis direstore (bertambah/berkurang sesuai pemulihan).')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                title="Batalkan/Hapus Transaksi"><i data-feather="rotate-ccw"></i>
                                                Batal</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">Belum ada riwayat transaksi logistik
                                        apotek.</td>
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
        .icon-sm {
            width: 14px;
            height: 14px;
        }
    </style>
@endsection