<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #111; margin: 0; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h2 { margin: 0; font-size: 14px; text-transform: uppercase; }
        .header h3 { margin: 4px 0; font-size: 12px; }
        .header p  { margin: 4px 0; font-size: 10px; color: #444; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        thead tr { background-color: #2563eb; color: white; }
        th { padding: 7px 6px; text-align: center; font-size: 10px; }
        td { padding: 5px 6px; border-bottom: 1px solid #e5e7eb; font-size: 10px; }
        tbody tr:nth-child(even) { background-color: #f8fafc; }
        .badge { padding: 2px 6px; border-radius: 4px; font-size: 9px; font-weight: bold; }
        .badge-success { background: #d1fae5; color: #065f46; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-danger  { background: #fee2e2; color: #991b1b; }
        .badge-secondary { background: #e5e7eb; color: #374151; }
        .tfoot td { font-weight: bold; background-color: #f1f5f9; }
        .ttd { margin-top: 40px; text-align: right; }
        .ttd p { margin: 0; line-height: 1.6; }
        .summary { margin-top: 8px; font-size: 10px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Kunjungan Pasien</h2>
        <h3>Poliklinik Politeknik Negeri Pontianak (POLNEP)</h3>
        <p>Periode: {{ date('d F Y', strtotime($startDate)) }} s/d {{ date('d F Y', strtotime($endDate)) }}</p>
        <p>Dicetak pada: {{ date('d F Y, H:i') }} WIB</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="14%">Tgl Daftar</th>
                <th width="16%">No Registrasi</th>
                <th width="22%">Nama Pasien</th>
                <th width="16%">Poliklinik</th>
                <th width="16%">Dokter</th>
                <th width="12%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pendaftarans as $index => $row)
            <tr>
                <td align="center">{{ $index + 1 }}</td>
                <td align="center">{{ $row->created_at->format('d/m/Y') }}</td>
                <td align="center">{{ $row->nomor_pendaftaran }}</td>
                <td>{{ $row->pasien->nama_lengkap ?? '-' }}</td>
                <td>{{ $row->poli->nama_poli ?? '-' }}</td>
                <td>{{ $row->dokter->nama_dokter ?? '-' }}</td>
                <td align="center">
                    @if($row->status_pendaftaran == 'selesai')
                        <span class="badge badge-success">Selesai</span>
                    @elseif($row->status_pendaftaran == 'diproses')
                        <span class="badge badge-warning">Diperiksa</span>
                    @elseif($row->status_pendaftaran == 'baru')
                        <span class="badge badge-danger">Menunggu</span>
                    @else
                        <span class="badge badge-secondary">{{ $row->status_pendaftaran }}</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="7" align="center" style="padding:15px; color:#888;">Tidak ada data kunjungan pada periode ini.</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="tfoot">
                <td colspan="6" align="right">Total Kunjungan Pasien Berobat:</td>
                <td align="center">{{ $pendaftarans->count() }} Pasien</td>
            </tr>
        </tfoot>
    </table>

    <div class="ttd">
        <p>Pontianak, {{ date('d F Y') }}</p>
        <p>Penanggung Jawab,</p>
        <br><br><br>
        <p><u>{{ Auth::user()->username }}</u></p>
        <p>{{ ucfirst(Auth::user()->role) }} Poliklinik</p>
    </div>
</body>
</html>
