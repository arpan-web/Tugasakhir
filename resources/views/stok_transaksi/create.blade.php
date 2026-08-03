@extends('layouts.app')

@section('title', 'Input Transaksi Stok Baru')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <a href="{{ route('stok_transaksi.index') }}" class="btn btn-outline-secondary"><i data-feather="arrow-left"></i> Kembali</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 pt-4 pb-0">
            <h4 class="mb-0">Form Input Transaksi Masuk/Keluar Obat</h4>
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

            <form action="{{ route('stok_transaksi.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6 mb-3">
                        <label for="tanggal_transaksi" class="form-label fw-bold">Tanggal Transaksi</label>
                        <input type="datetime-local" class="form-control" id="tanggal_transaksi" name="tanggal_transaksi" value="{{ old('tanggal_transaksi', date('Y-m-d\TH:i')) }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="id_obat" class="form-label fw-bold">Pilih Obat <span class="text-danger">*</span></label>
                        <select class="form-select select2-enable" id="id_obat" name="id_obat" required>
                            <option value="">-- Pilih Obat --</option>
                            @foreach($obats as $obat)
                                <option value="{{ $obat->id_obat }}" {{ old('id_obat') == $obat->id_obat ? 'selected' : '' }}>
                                    [{{ $obat->kode_obat }}] {{ $obat->nama_obat }} (Tersedia: {{ $obat->stok_tersedia }} {{ $obat->satuan }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Jenis Transaksi <span class="text-danger">*</span></label>
                        <div class="d-flex mt-2">
                            <div class="form-check me-4">
                                <input class="form-check-input" type="radio" name="jenis_transaksi" id="trx_masuk" value="masuk" {{ old('jenis_transaksi', 'masuk') == 'masuk' ? 'checked' : '' }}>
                                <label class="form-check-label text-success fw-bold" for="trx_masuk">
                                    <i data-feather="arrow-down-left" class="icon-sm"></i> Stok Masuk (Restock)
                                </label>
                            </div>
                            <div class="form-check align-items-center">
                                <input class="form-check-input" type="radio" name="jenis_transaksi" id="trx_keluar" value="keluar" {{ old('jenis_transaksi') == 'keluar' ? 'checked' : '' }}>
                                <label class="form-check-label text-danger fw-bold" for="trx_keluar">
                                    <i data-feather="arrow-up-right" class="icon-sm"></i> Stok Keluar (Expired/Rusak/Retur)
                                </label>
                            </div>
                        </div>
                        <div class="form-text mt-2"><i class="text-info">*Catatan: Stok keluar karena penyerahan obat ke pasien dari resep ditangani secara otomatis oleh Sistem Pemeriksaan, tidak perlu diinput manual disini kecuali ada selisih.</i></div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="jumlah" class="form-label fw-bold">Jumlah <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="jumlah" name="jumlah" value="{{ old('jumlah', 1) }}" min="1" required>
                            <span class="input-group-text bg-light text-muted" id="satuan-label">satuan</span>
                        </div>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="keterangan" class="form-label">Keterangan Tambahan / Asal/Tujuan Obat</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="3" placeholder="Contoh: Penerimaan obat dari supplier Kimia Farma, Obat rusak kedaluwarsa, dll.">{{ old('keterangan') }}</textarea>
                    </div>

                    {{-- Field Kadaluarsa (Hanya aktif saat jenis transaksi = masuk) --}}
                    <div class="col-md-6 mb-3" id="kadaluarsa-field" style="display: none;">
                        <label for="tanggal_kadaluarsa" class="form-label fw-bold">
                            <i data-feather="calendar" class="icon-sm text-warning"></i> Tanggal Kadaluarsa Batch
                            <span class="badge bg-warning text-dark ms-1" style="font-size:10px;">Opsional</span>
                        </label>
                        <input type="date" class="form-control" id="tanggal_kadaluarsa" name="tanggal_kadaluarsa" value="{{ old('tanggal_kadaluarsa') }}">
                        <div class="form-text text-muted">*Isi sesuai tanggal expired kemasan obat. Digunakan untuk peringatan notifikasi & prioritas pengeluaran (FEFO).</div>
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary"><i data-feather="save" class="me-1"></i> Simpan Transaksi Catat Stok</button>
                </div>
            </form>
        </div>
    </div>
</div>
<style>
    .icon-sm { width: 16px; height: 16px; top: -1px; position: relative;}
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectObat = document.getElementById('id_obat');
        const satuanLabel = document.getElementById('satuan-label');
        const radioMasuk = document.getElementById('trx_masuk');
        const radioKeluar = document.getElementById('trx_keluar');
        const kadaluarsaField = document.getElementById('kadaluarsa-field');

        function updateSatuan() {
            const selectedOption = selectObat.options[selectObat.selectedIndex];
            if (selectedOption.value !== "") {
                const text = selectedOption.text;
                const match = text.match(/\(Tersedia:\s+\d+\s+(\w+)\)/);
                if(match && match[1]) {
                    satuanLabel.textContent = match[1];
                } else {
                    satuanLabel.textContent = "satuan";
                }
            } else {
                satuanLabel.textContent = "satuan";
            }
        }

        function toggleKadaluarsa() {
            if (radioMasuk.checked) {
                kadaluarsaField.style.display = 'block';
            } else {
                kadaluarsaField.style.display = 'none';
            }
        }

        selectObat.addEventListener('change', updateSatuan);
        radioMasuk.addEventListener('change', toggleKadaluarsa);
        radioKeluar.addEventListener('change', toggleKadaluarsa);

        updateSatuan();
        toggleKadaluarsa();
    });
</script>

@endsection
