<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Poliklinik Polnep')</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://unpkg.com/feather-icons"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap');

        :root {
            --primary-color: #3b82f6;
            --primary-hover: #2563eb;
            --primary-rgb: 59, 130, 246;
            --success-color: #10b981;
            --success-rgb: 16, 185, 129;
            --danger-color: #ef4444;
            --danger-rgb: 239, 68, 68;
            --warning-color: #f59e0b;
            --warning-rgb: 245, 158, 11;
            --info-color: #06b6d4;
            --sidebar-bg: #0f172a;
            --sidebar-header: #1e293b;
            --sidebar-text: #94a3b8;
            --sidebar-hover-bg: rgba(255, 255, 255, 0.04);
            --sidebar-active-bg: rgba(59, 130, 246, 0.15);
            --sidebar-active-text: #60a5fa;
            --card-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05), 0 2px 5px -1px rgba(0, 0, 0, 0.03);
            --border-radius: 12px;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #f8fafc;
            color: #334155;
            overflow-x: hidden;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Sidebar Styling */
        #sidebar {
            min-width: 260px;
            max-width: 260px;
            height: 100vh;
            position: sticky;
            top: 0;
            overflow-y: auto;
            overflow-x: hidden;
            background: linear-gradient(180deg, var(--sidebar-bg) 0%, #090d16 100%);
            color: #e2e8f0;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.15);
            z-index: 100;
            flex-shrink: 0;
        }

        #sidebar .sidebar-brand {
            padding: 24px;
            background: rgba(15, 23, 42, 0.6);
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }

        #sidebar .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        #sidebar .sidebar-logo h3 {
            font-size: 1.25rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            margin: 0;
            background: linear-gradient(135deg, #60a5fa 0%, #38bdf8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        #sidebar .user-info {
            font-size: 0.8rem;
            color: var(--sidebar-text);
            margin-top: 4px;
            display: block;
        }

        #sidebar ul.components {
            padding: 16px 12px;
        }

        #sidebar ul li {
            margin-bottom: 4px;
        }

        #sidebar ul li.sidebar-header {
            font-size: 0.7rem;
            letter-spacing: 1px;
            font-weight: 700;
            color: #64748b !important;
            padding: 16px 16px 4px 16px;
            background: none !important;
            border: none !important;
            margin-top: 15px;
        }

        #sidebar ul li a {
            padding: 11px 16px;
            font-size: 0.95rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--sidebar-text);
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        #sidebar ul li a i {
            width: 18px;
            height: 18px;
            stroke-width: 2;
            transition: transform 0.2s ease;
        }

        #sidebar ul li a:hover {
            color: #fff;
            background: var(--sidebar-hover-bg);
        }

        #sidebar ul li a:hover i {
            transform: translateX(2px);
        }

        #sidebar ul li.active>a {
            color: var(--sidebar-active-text);
            background: var(--sidebar-active-bg);
            font-weight: 600;
        }

        #sidebar ul li.active>a i {
            color: var(--sidebar-active-text);
        }

        /* Submenu */
        #sidebar ul li ul {
            padding-left: 12px;
            margin-top: 4px;
            background: rgba(0, 0, 0, 0.15);
            border-radius: 8px;
        }

        #sidebar ul li ul li a {
            padding: 8px 16px 8px 36px;
            font-size: 0.875rem;
        }

        /* Main Layout Wrapper */
        .wrapper {
            display: flex;
            width: 100%;
            align-items: flex-start;
        }

        #content {
            width: 100%;
            padding: 30px;
            min-height: 100vh;
            transition: all 0.3s;
            background-color: #f8fafc;
            overflow-x: hidden;
        }

        /* Top Navbar */
        .navbar-custom {
            background: rgba(255, 255, 255, 0.8) !important;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 12px;
            padding: 12px 24px;
            box-shadow: var(--card-shadow);
        }

        .navbar-custom .navbar-brand {
            font-weight: 600;
            color: #475569;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Form Styling Overrides */
        .form-control,
        .form-select {
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            padding: 10px 14px;
            font-size: 0.9rem;
            color: #334155;
            background-color: #fff;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(var(--primary-rgb), 0.15);
            outline: 0;
        }

        .form-label {
            font-size: 0.875rem;
            color: #475569;
            margin-bottom: 6px;
        }

        /* Card Styling Overrides */
        .card {
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(226, 232, 240, 0.8);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            overflow: hidden;
        }

        .card-header {
            font-weight: 600;
            color: #1e293b;
            border-bottom: 1px solid #f1f5f9;
        }

        /* Button Styling Overrides */
        .btn {
            border-radius: 8px;
            padding: 9px 18px;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            box-shadow: 0 4px 10px rgba(var(--primary-rgb), 0.2);
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 6px 12px rgba(var(--primary-rgb), 0.3);
        }

        .btn-outline-secondary {
            border-color: #cbd5e1;
            color: #475569;
        }

        .btn-outline-secondary:hover {
            background-color: #f1f5f9;
            border-color: #cbd5e1;
            color: #334155;
        }

        /* Alert Styling */
        .alert {
            border-radius: 8px;
            border: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
        }

        /* Custom Table Styles */
        .table {
            margin-bottom: 0;
        }

        .table th {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
            color: #64748b;
            background-color: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            padding: 12px 16px;
        }

        .table td {
            padding: 14px 16px;
            font-size: 0.875rem;
            color: #334155;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        .table-hover tbody tr:hover {
            background-color: #f8fafc;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(248, 250, 252, 0.5);
        }

        /* Custom Badges */
        .badge {
            padding: 6px 12px;
            font-weight: 600;
            font-size: 0.75rem;
            border-radius: 30px;
        }

        .bg-primary {
            background-color: rgba(var(--primary-rgb), 0.1) !important;
            color: var(--primary-color) !important;
        }

        .bg-success {
            background-color: rgba(var(--success-rgb), 0.1) !important;
            color: var(--success-color) !important;
        }

        .bg-danger {
            background-color: rgba(var(--danger-rgb), 0.1) !important;
            color: var(--danger-color) !important;
        }

        .bg-warning {
            background-color: rgba(var(--warning-rgb), 0.1) !important;
            color: var(--warning-color) !important;
        }

        .bg-info {
            background-color: rgba(6, 182, 212, 0.1) !important;
            color: #0891b2 !important;
        }
    </style>
</head>

<body>

    <div class="wrapper">
        <!-- Sidebar  -->
        <nav id="sidebar">
            <div class="sidebar-brand">
                <div class="sidebar-logo">
                    <img src="{{ asset('logo-polnep.png') }}" alt="Logo POLNEP"
                        style="height: 32px; width: auto; object-fit: contain;">
                    <h3>POLIKLINIK POLNEP</h3>
                </div>
                <span class="user-info">
                    {{ auth()->user()->nama_user ?? 'Guest' }}
                    <span class="badge bg-primary ms-1 py-0.5 px-1.5"
                        style="font-size: 0.65rem; border-radius: 4px; text-transform: uppercase;">
                        {{ auth()->user()->role ?? '' }}
                    </span>
                </span>
            </div>

            <ul class="list-unstyled components">
                <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}"><i data-feather="home"></i> Dashboard</a>
                </li>

                @if(auth()->user()->role == 'admin')
                    <li
                        class="{{ request()->is('poli*') || request()->is('dokter*') || request()->is('perawat*') || request()->is('obat*') ? 'active' : '' }}">
                        <a href="#dataMasterSubmenu" data-bs-toggle="collapse" class="dropdown-toggle"><i
                                data-feather="database"></i> Data Master</a>
                        <ul class="collapse list-unstyled {{ request()->is('poli*') || request()->is('dokter*') || request()->is('perawat*') || request()->is('obat*') ? 'show' : '' }}"
                            id="dataMasterSubmenu">
                            <li class="{{ request()->routeIs('poli.*') ? 'active' : '' }}"><a
                                    href="{{ route('poli.index') }}">Poli</a></li>
                            <li class="{{ request()->routeIs('dokter.*') ? 'active' : '' }}"><a
                                    href="{{ route('dokter.index') }}">Dokter</a></li>
                            <li class="{{ request()->routeIs('perawat.*') ? 'active' : '' }}"><a
                                    href="{{ route('perawat.index') }}">Perawat</a></li>
                            <li class="{{ request()->routeIs('obat.*') ? 'active' : '' }}"><a
                                    href="{{ route('obat.index') }}">Obat</a></li>
                        </ul>
                    </li>
                @endif

                @if(in_array(auth()->user()->role, ['admin', 'perawat', 'dokter']))
                    <li class="{{ request()->routeIs('pasien.*') ? 'active' : '' }}">
                        <a href="{{ route('pasien.index') }}"><i data-feather="users"></i> Data Pasien</a>
                    </li>
                @endif

                @if(in_array(auth()->user()->role, ['admin', 'perawat']))
                    <li class="{{ request()->routeIs('pendaftaran.*') ? 'active' : '' }}">
                        <a href="{{ route('pendaftaran.index') }}"><i data-feather="clipboard"></i> Pendaftaran Antrian</a>
                    </li>
                    <li class="{{ request()->routeIs('stok_transaksi.*') ? 'active' : '' }}">
                        <a href="{{ route('stok_transaksi.index') }}"><i data-feather="package"></i> Transaksi Obat</a>
                    </li>
                @endif

                @if(in_array(auth()->user()->role, ['admin', 'dokter']))
                    <li class="{{ request()->routeIs('diagnosa.*') ? 'active' : '' }}">
                        <a href="{{ route('diagnosa.index') }}"><i data-feather="user-check"></i> Pemeriksaan Pasien</a>
                    </li>
                @endif



                @if(in_array(auth()->user()->role, ['admin']))
                    <li class="sidebar-header mt-3 text-muted px-3" style="font-size: 0.7rem; letter-spacing: 1px;">
                        <small>LAPORAN MANAJEMEN</small></li>
                    <li class="{{ request()->routeIs('laporan.index') ? 'active' : '' }}">
                        <a href="{{ route('laporan.index') }}"><i data-feather="bar-chart-2"></i> Laporan Eksekutif</a>
                    </li>
                    <li class="{{ request()->routeIs('laporan.kunjungan') ? 'active' : '' }}">
                        <a href="{{ route('laporan.kunjungan') }}"><i data-feather="trending-up"></i> Lap. Kunjungan</a>
                    </li>
                    <li class="{{ request()->routeIs('laporan.diagnosa') ? 'active' : '' }}">
                        <a href="{{ route('laporan.diagnosa') }}"><i data-feather="activity"></i> Lap. Diagnosa</a>
                    </li>
                    <li class="{{ request()->routeIs('laporan.obat') ? 'active' : '' }}">
                        <a href="{{ route('laporan.obat') }}"><i data-feather="archive"></i> Lap. Obat</a>
                    </li>
                @endif
            </ul>

            <div class="px-4 py-3">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger w-100 btn-sm py-2"
                        style="font-size: 0.85rem;"><i data-feather="log-out"
                            style="width: 14px; height: 14px; margin-right: 4px;"></i> Logout</button>
                </form>
            </div>
        </nav>

        <!-- Page Content  -->
        <div id="content">

            <nav class="navbar navbar-expand-lg navbar-light bg-light rounded shadow-sm mb-4 navbar-custom">
                <div class="container-fluid p-0">
                    <span class="navbar-brand mb-0"><i data-feather="calendar"
                            style="width: 16px; height: 16px; color: var(--primary-color);"></i> Tanggal:
                        {{ date('d/m/Y') }}</span>

                    {{-- Bell Notifikasi --}}
                    @php
                        // Notifikasi umum dari tabel (antrian, dll) selain stok & kadaluarsa
                        $notifBelumDibaca = \App\Models\Notifikasi::where('status', 'belum_dibaca')
                            ->whereNotIn('tipe', ['stok', 'kadaluarsa'])
                            ->latest()
                            ->get();

                        // Notifikasi stok kritis: real-time, persisten selama stok <= minimal
                        $obatKritis = \App\Models\Obat::whereRaw('stok_tersedia <= stok_minimal')->get();

                        // Notifikasi batch kadaluarsa: real-time & persisten selama sisa_stok > 0 dan <= 30 hari expired
                        $batchExpired = \App\Models\StokTransaksi::with('obat')
                            ->where('jenis_transaksi', 'masuk')
                            ->where('sisa_stok', '>', 0)
                            ->whereNotNull('tanggal_kadaluarsa')
                            ->whereDate('tanggal_kadaluarsa', '<=', \Carbon\Carbon::today()->addDays(30))
                            ->orderBy('tanggal_kadaluarsa', 'asc')
                            ->get();

                        $notifCount = $notifBelumDibaca->count() + $obatKritis->count() + $batchExpired->count();
                    @endphp
                    <div class="dropdown ms-auto me-2">
                        <button class="btn btn-light position-relative border-0 shadow-none" type="button"
                            id="notifDropdown" data-bs-toggle="dropdown" aria-expanded="false"
                            style="background: transparent;">
                            <i data-feather="bell" style="width: 20px; height: 20px; color: #64748b;"></i>
                            @if($notifCount > 0)
                                <span
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                    style="font-size: 0.65rem; background-color: #ef4444 !important; color: white !important; padding: 3px 6px;">
                                    {{ $notifCount > 9 ? '9+' : $notifCount }}
                                </span>
                            @endif
                        </button>
                        <div class="dropdown-menu dropdown-menu-end shadow border-0 p-0" aria-labelledby="notifDropdown"
                            style="min-width: 340px; border-radius: 12px; overflow: hidden;">
                            {{-- Header dropdown --}}
                            <div class="d-flex align-items-center justify-content-between px-3 py-2"
                                style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                                <span class="fw-semibold" style="font-size: 0.875rem; color: #334155;">
                                    <i data-feather="bell" style="width: 14px; height: 14px;"></i>
                                    Notifikasi
                                    @if($notifCount > 0)
                                        <span class="badge ms-1"
                                            style="background: rgba(239,68,68,0.1); color: #ef4444; font-size: 0.7rem; border-radius: 20px;">{{ $notifCount }}
                                            baru</span>
                                    @endif
                                </span>
                                @if($notifCount > 0)
                                    <form action="{{ route('notifikasi.bacaSemua') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-link btn-sm p-0 text-decoration-none"
                                            style="font-size: 0.75rem; color: var(--primary-color);">Tandai semua
                                            dibaca</button>
                                    </form>
                                @endif
                            </div>

                            {{-- List notifikasi --}}
                            <div style="max-height: 320px; overflow-y: auto;">

                                {{-- BATCH EXPIRED / MENDEKATI EXPIRED --}}
                                @foreach($batchExpired as $be)
                                    @php
                                        $tglExp = \Carbon\Carbon::parse($be->tanggal_kadaluarsa);
                                        $hariSisa = \Carbon\Carbon::today()->diffInDays($tglExp, false);
                                        $isExpired = \Carbon\Carbon::today()->gt($tglExp);
                                    @endphp
                                    <a href="{{ route('stok_transaksi.index') }}" class="dropdown-item d-flex align-items-start gap-2 py-2 px-3 text-start text-decoration-none"
                                        style="border-bottom: 1px solid #f1f5f9 !important; background: {{ $isExpired ? '#fef2f2' : '#fffbeb' }};">
                                        <span class="mt-1 flex-shrink-0" style="width: 8px; height: 8px; border-radius: 50%; background: {{ $isExpired ? '#ef4444' : '#f59e0b' }}; display: inline-block;"></span>
                                        <div>
                                            <div style="font-size: 0.8rem; color: #334155; white-space: normal; line-height: 1.4;">
                                                @if($isExpired)
                                                    🚫 <strong>'{{ $be->obat->nama_obat ?? 'Obat' }}'</strong> SUDAH EXPIRED ({{ $tglExp->format('d/m/Y') }})! Sisa batch: <strong>{{ $be->sisa_stok }} {{ $be->obat->satuan ?? '' }}</strong>
                                                @else
                                                    ⚠ <strong>'{{ $be->obat->nama_obat ?? 'Obat' }}'</strong> expired dlm {{ $hariSisa }} hari ({{ $tglExp->format('d/m/Y') }}). Sisa batch: <strong>{{ $be->sisa_stok }} {{ $be->obat->satuan ?? '' }}</strong>
                                                @endif
                                            </div>
                                            <div style="font-size: 0.7rem; color: {{ $isExpired ? '#ef4444' : '#d97706' }}; margin-top: 2px; font-weight: 500;">
                                                {{ $isExpired ? 'Klik untuk musnahkan stok →' : 'Gunakan obat ini lebih dulu (FEFO)' }}
                                            </div>
                                        </div>
                                    </a>
                                @endforeach

                                {{-- STOK KRITIS: ditampilkan real-time, tetap muncul selama stok belum ditambah --}}
                                @foreach($obatKritis as $ok)
                                    <a href="{{ route('obat.index') }}" class="dropdown-item d-flex align-items-start gap-2 py-2 px-3 text-start text-decoration-none"
                                        style="border-bottom: 1px solid #f1f5f9 !important; background: #fffbeb;">
                                        <span class="mt-1 flex-shrink-0" style="width: 8px; height: 8px; border-radius: 50%; background: #f59e0b; display: inline-block;"></span>
                                        <div>
                                            <div style="font-size: 0.8rem; color: #334155; white-space: normal; line-height: 1.4;">
                                                ⚠ Stok <strong>'{{ $ok->nama_obat }}'</strong> menipis! Sisa: <strong>{{ $ok->stok_tersedia }} {{ $ok->satuan }}</strong> (min: {{ $ok->stok_minimal }})
                                            </div>
                                            <div style="font-size: 0.7rem; color: #f59e0b; margin-top: 2px; font-weight: 500;">
                                                Klik untuk kelola stok → akan hilang setelah restock
                                            </div>
                                        </div>
                                    </a>
                                @endforeach

                                {{-- Notifikasi lain (antrian, dll) dari tabel notifikasi --}}
                                @forelse($notifBelumDibaca->where('tipe', '!=', 'stok') as $notif)
                                    <form action="{{ route('notifikasi.baca', $notif->id_notif) }}" method="POST"
                                        class="d-block">
                                        @csrf
                                        <button type="submit"
                                            class="dropdown-item d-flex align-items-start gap-2 py-2 px-3 text-start border-0 bg-transparent w-100"
                                            style="border-bottom: 1px solid #f1f5f9 !important;">
                                            <span class="mt-1 flex-shrink-0"
                                                style="width: 8px; height: 8px; border-radius: 50%; background: #3b82f6; display: inline-block;"></span>
                                            <div>
                                                <div
                                                    style="font-size: 0.8rem; color: #334155; white-space: normal; line-height: 1.4;">
                                                    {{ $notif->pesan }}</div>
                                                <div style="font-size: 0.7rem; color: #94a3b8; margin-top: 2px;">
                                                    {{ $notif->created_at->diffForHumans() }}</div>
                                            </div>
                                        </button>
                                    </form>
                                @empty
                                @endforelse

                                @if($notifCount === 0)
                                    <div class="text-center py-4" style="color: #94a3b8; font-size: 0.825rem;">
                                        <i data-feather="check-circle"
                                            style="width: 24px; height: 24px; display: block; margin: 0 auto 6px;"></i>
                                        Tidak ada notifikasi baru
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        feather.replace()
    </script>
</body>

</html>