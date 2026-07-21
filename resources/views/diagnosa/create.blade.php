@extends('layouts.app')

@section('title', 'Pemeriksaan Pasien')

@section('content')
<div class="container-fluid">
    <div class="mb-3">
        <a href="{{ route('diagnosa.index') }}" class="btn btn-outline-secondary"><i data-feather="arrow-left"></i> Kembali ke Antrian</a>
    </div>

    <!-- Informasi Rekam Medis (Readonly) -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">Informasi Pasien & Pemeriksaan Awal</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td width="35%" class="text-muted">Nama Pasien</td>
                            <td class="fw-bold">: {{ $pendaftaran->pasien->nama_lengkap }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">No. Rekam Medis</td>
                            <td>: {{ $pendaftaran->pasien->nomor_pasien }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Poli / Dokter</td>
                            <td>: {{ $pendaftaran->poli->nama_poli ?? '-' }} / {{ $pendaftaran->dokter->nama_dokter ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td width="30%" class="text-muted">Keluhan Utama</td>
                            <td class="fw-bold text-danger">: {{ $pendaftaran->keluhan }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Suhu / BB / TB</td>
                            <td>: {{ $pendaftaran->suhu_tubuh ?? '-' }} °C / {{ $pendaftaran->berat_badan ?? '-' }} kg / {{ $pendaftaran->tinggi_badan ?? '-' }} cm</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Waktu Daftar</td>
                            <td>: {{ $pendaftaran->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('diagnosa.store') }}" method="POST" id="form-diagnosa">
        @csrf
        <input type="hidden" name="id_pendaftaran" value="{{ $pendaftaran->id_pendaftaran }}">
        
        <div class="row">
            <!-- Kolom Diagnosa -->
            <div class="col-lg-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white">
                        <h5 class="mb-0 text-primary">Diagnosa & Tindakan Dokter</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="diagnosa_text" class="form-label fw-bold">Hasil Diagnosa (ICD-10 / Nama Penyakit) <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="diagnosa_text" name="diagnosa_text" rows="5" required placeholder="Contoh: Asma Bronkial persisten sedang..">{{ old('diagnosa_text') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="tindakan" class="form-label fw-bold">Tindakan Medis / Advice <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="tindakan" name="tindakan" rows="5" required placeholder="Contoh: Pasang oksigen 3 lpm, injeksi Salbutamol..">{{ old('tindakan') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom Resep Obat -->
            <div class="col-lg-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-success">Resep Obat Farmasi</h5>
                        <button type="button" class="btn btn-sm btn-outline-success" id="btn-tambah-obat">
                            <i data-feather="plus"></i> Tambah Obat
                        </button>
                    </div>
                    <div class="card-body" id="tempat-resep">
                        <div class="alert alert-info py-2" id="alert-no-obat">Belum ada obat ditambahkan. Klik tombol Tambah Obat di kanan atas.</div>
                        
                        <!-- Baris Obat akan dimasukkan kesini via JS -->
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body text-end">
                <button type="submit" class="btn btn-primary btn-lg" id="btn-simpan-diagnosa">
                    <i data-feather="check-circle" class="me-1"></i> Simpan Pemeriksaan & Selesaikan
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Template Baris Obat (Hidden) -->
<div class="d-none" id="template-baris-obat">
    <div class="row g-2 mb-3 border-bottom pb-3 baris-obat-item">
        <div class="col-md-5">
            <label class="form-label small text-muted mb-1">Pilih Obat</label>
            <select class="form-select" name="obat_id[]">
                <option value="">-- Pilih Obat --</option>
                @foreach($obats as $obat)
                    <option value="{{ $obat->id_obat }}">
                        {{ $obat->nama_obat }} (Stok: {{ $obat->stok_tersedia }} {{ $obat->satuan }})
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label small text-muted mb-1">Jumlah</label>
            <input type="number" class="form-control" name="jumlah[]" value="1" min="1">
        </div>
        <div class="col-md-4">
            <label class="form-label small text-muted mb-1">Dosis (Aturan Pakai)</label>
            <input type="text" class="form-control" name="dosis[]" placeholder="misal: 3x1 Sesudah Makan">
        </div>
        <div class="col-md-1 d-flex align-items-end">
            <button type="button" class="btn btn-outline-danger btn-hapus-obat" title="Singkirkan Obat"><i data-feather="trash"></i></button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnTambah = document.getElementById('btn-tambah-obat');
        const tempatResep = document.getElementById('tempat-resep');
        const templateBaris = document.getElementById('template-baris-obat').innerHTML;
        const alertNoObat = document.getElementById('alert-no-obat');

        const formDiagnosa = document.getElementById('form-diagnosa');

        btnTambah.addEventListener('click', function() {
            alertNoObat.style.display = 'none';
            
            // Insert new HTML block
            tempatResep.insertAdjacentHTML('beforeend', templateBaris);
            feather.replace(); // Refresh icons
            
            // Set input newly added to required dynamically
            const inputs = tempatResep.querySelectorAll('.baris-obat-item:last-child input, .baris-obat-item:last-child select');
            inputs.forEach(input => input.setAttribute('required', 'required'));
        });

        tempatResep.addEventListener('click', function(e) {
            if (e.target.closest('.btn-hapus-obat')) {
                const item = e.target.closest('.baris-obat-item');
                item.remove();
                
                // Show message if empty again
                if (tempatResep.querySelectorAll('.baris-obat-item').length === 0) {
                    alertNoObat.style.display = 'block';
                }
            }
        });

        // Hapus elemen tersembunyi saat di-submit dan validasi native form
        formDiagnosa.addEventListener('submit', function(e) {
            if(!formDiagnosa.checkValidity()) {
                e.preventDefault();
                formDiagnosa.reportValidity();
                return false;
            }

            // Minta konfirmasi
            if (!confirm('Apakah diagnosa dan resep sudah diisi dengan valid? Aksi ini akan memotong persediaan stok farmasi secara langsung.')) {
                e.preventDefault();
                return false;
            }

            // Remove hidden template right before submit so it wouldn't send blank arrays unexpectedly
            const hiddenTemplate = document.getElementById('template-baris-obat');
            if(hiddenTemplate) hiddenTemplate.remove();
        });
    });
</script>
@endsection
