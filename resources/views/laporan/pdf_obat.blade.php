<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #111; margin: 0; }
        .header { text-align: center; margin-bottom: 16px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h2 { margin: 0; font-size: 14px; text-transform: uppercase; }
        .header h3 { margin: 4px 0; font-size: 12px; }
        .header p  { margin: 4px 0; font-size: 10px; color: #444; }
        .section-title { font-size: 11px; font-weight: bold; margin: 16px 0 6px 0;
                         padding: 4px 8px; background: #1e3a8a; color: white; border-radius: 3px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
        thead tr { background-color: #1e3a8a; color: white; }
        th { padding: 6px 5px; text-align: center; font-size: 10px; }
        td { padding: 5px 5px; border-bottom: 1px solid #e5e7eb; font-size: 10px; }
        tbody tr:nth-child(even) { background-color: #f8fafc; }
        .badge-masuk  { background: #d1fae5; color: #065f46; padding: 2px 5px; border-radius: 4px; font-size: 9px; font-weight:bold; }
        .badge-keluar { background: #fee2e2; color: #991b1b; padding: 2px 5px; border-radius: 4px; font-size: 9px; font-weight:bold; }
        .tfoot td { font-weight: bold; background-color: #f1f5f9; }
        .ttd { margin-top: 40px; text-align: right; }
        .ttd p { margin: 0; line-height: 1.6; }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Mutasi dan Penggunaan Obat / Farmasi</h2>
        <h3>Poliklinik Politeknik Negeri Pontianak (POLNEP)</h3>
        <p>Periode: {{ date('d F Y', strtotime($startDate)) }} s/d {{ date('d F Y', strtotime($endDate)) }}</p>
        <p>Dicetak pada: {{ date('d F Y, H:i') }} WIB</p>
    </div>

    {{-- Bagian A: Resep Dokter --}}
    <div class="section-title">A. Rekap Total Obat dari Resep Dokter</div>
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Kode Obat</th>
                <th width="50%">Nama Obat</th>
                <th width="30%">Total Obat Keluar (ke Pasien)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($penggunaanObat as $index => $penggunaan)
            <tr>
                <td align="center">{{ $index + 1 }}</td>
                <td align="center">{{ $penggunaan->obat->kode_obat ?? '-' }}</td>
                <td>{{ $penggunaan->obat->nama_obat ?? 'Unknown' }}</td>
                <td align="center"><strong>{{ $penggunaan->total_digunakan }} {{ $penggunaan->obat->satuan ?? 'pcs' }}</strong></td>
            </tr>
            @empty
            <tr><td colspan="4" align="center" style="padding:12px; color:#888;">Belum ada obat yang diresepkan pada periode ini.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- Bagian B: Mutasi Logistik --}}
    <div class="section-title">B. Riwayat Mutasi Logistik Master Farmasi</div>
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="12%">Tanggal</th>
                <th width="35%">Nama Obat</th>
                <th width="13%">Tipe Mutasi</th>
                <th width="15%">Jumlah</th>
                <th width="20%">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($stokTransaksis as $index => $trx)
            <tr>
                <td align="center">{{ $index + 1 }}</td>
                <td align="center">{{ date('d/m/Y', strtotime($trx->tanggal_transaksi)) }}</td>
                <td>{{ $trx->obat->nama_obat ?? '-' }}</td>
                <td align="center">
                    @if($trx->jenis_transaksi == 'masuk')
                        <span class="badge-masuk">Masuk</span>
                    @else
                        <span class="badge-keluar">Keluar</span>
                    @endif
                </td>
                <td align="center">{{ $trx->jumlah }} {{ $trx->obat->satuan ?? '' }}</td>
                <td>{{ $trx->keterangan ?? '-' }}</td>
            </tr>
            @empty
            <tr><td colspan="6" align="center" style="padding:12px; color:#888;">Tidak ada catatan mutasi pada periode ini.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="ttd">
        <p>Pontianak, {{ date('d F Y') }}</p>
        <p>Apoteker / Bagian Logistik,</p>
        <br><br><br>
        <p><u>{{ Auth::user()->username }}</u></p>
        <p>{{ ucfirst(Auth::user()->role) }} Poliklinik</p>
    </div>
</body>
</html>
