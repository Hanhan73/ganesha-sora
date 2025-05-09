<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Ganesha Sora</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .form-action-buttons {
            display: flex;
            gap: 10px; /* atau 0.5rem, sesuai selera */
            flex-wrap: wrap;
        }

        :root {
            --primary-color: #4e73df;
            --secondary-color: #f8f9fc;
            --accent-color: #2e59d9;
            --text-color: #5a5c69;
        }
        
        body {
            display: flex;
            min-height: 100vh;
            background-color: #f8f9fc;
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }

        .sidebar {
            width: 250px;
            background: linear-gradient(180deg, var(--primary-color) 10%, #224abe 100%);
            color: white;
            padding: 20px 0;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }

        .sidebar-header {
            padding: 0 20px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.15);
        }

        .sidebar a, .sidebar button {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s;
        }

        .sidebar a:hover, .sidebar button:hover {
            color: white;
            background-color: rgba(255,255,255,0.1);
        }

        .sidebar .nav-link {
            padding: 10px 20px;
            font-weight: 600;
        }

        .sidebar .dropdown-menu {
            background-color: var(--primary-color);
            border: none;
            box-shadow: none;
        }

        .sidebar .dropdown-item {
            color: rgba(255,255,255,0.8);
            padding: 8px 30px;
        }

        .sidebar .dropdown-item:hover {
            background-color: transparent;
            color: white;
        }

        .content {
            flex: 1;
            padding: 30px;
            background-color: #f8f9fc;
        }

        .card {
            border: none;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            margin-bottom: 20px;
        }

        .card-header {
            background-color: #f8f9fc;
            border-bottom: 1px solid #e3e6f0;
            font-weight: 700;
            padding: 1rem 1.35rem;
        }

        .table-responsive {
            border-radius: 0.35rem;
            overflow: hidden;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 15px;
        }

        .table tbody tr {
            transition: all 0.2s;
        }

        .table tbody tr:hover {
            background-color: rgba(78, 115, 223, 0.05);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
        }

        .dropdown-toggle::after {
            transition: transform 0.2s;
        }

        .dropdown-toggle[aria-expanded="true"]::after {
            transform: rotate(180deg);
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }

        .action-buttons .btn {
            margin-right: 5px;
            min-width: 70px;
        }

        .sidebar-brand {
            font-weight: 800;
            font-size: 1.2rem;
            color: white !important;
            text-decoration: none;
            display: flex;
            align-items: center;
        }

        .sidebar-brand-icon {
            margin-right: 10px;
            font-size: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <a href="/" class="sidebar-brand">
                <i class="bi bi-box-seam sidebar-brand-icon"></i>
                <span>Ganesha Sora</span>
            </a>
        </div>

        @php
            $role = auth()->user()->role;
        @endphp

        <div class="nav flex-column">
            <a href="/" class="nav-link d-flex align-items-center">
                <i class="bi bi-speedometer2 me-2"></i>
                Dashboard
            </a>

            @if ($role === 'admin')
                <div class="dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-database me-2"></i>
                        Data Master
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="/bahanbaku"><i class="bi bi-box me-2"></i> Bahan Baku</a></li>
                        <li><a class="dropdown-item" href="/produkjadi"><i class="bi bi-box-seam me-2"></i> Produk Jadi</a></li>
                        <li><a class="dropdown-item" href="/supplier"><i class="bi bi-truck me-2"></i> Supplier</a></li>
                        <li><a class="dropdown-item" href="/pelanggan"><i class="bi bi-people me-2"></i> Pelanggan</a></li>
                        <li><a class="dropdown-item" href="/stokbarang"><i class="bi bi-clipboard-data me-2"></i> Stok Barang</a></li>
                    </ul>
                </div>
            @endif

            @if ($role === 'gudang')
                <a href="/stokbarang" class="nav-link d-flex align-items-center">
                    <i class="bi bi-clipboard-data me-2"></i>
                    Stok Barang
                </a>
            @endif

            @if (in_array($role, ['admin', 'direktur']))
                <div class="dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-arrow-left-right me-2"></i>
                        Transaksi
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="/pembelian"><i class="bi bi-cart me-2"></i> Pembelian</a></li>
                        <li><a class="dropdown-item" href="/penjualan"><i class="bi bi-cash me-2"></i> Penjualan</a></li>
                    </ul>
                </div>
            @endif

            @if (in_array($role, ['admin', 'produksi']))
                <div class="dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-gear me-2"></i>
                        Produksi
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="/permintaanbahanbaku"><i class="bi bi-clipboard-check me-2"></i> Permintaan Bahan</a></li>
                        <li><a class="dropdown-item" href="/produksi"><i class="bi bi-cpu me-2"></i> Produksi</a></li>
                    </ul>
                </div>
            @endif

            @if (in_array($role, ['admin', 'direktur']))
                <div class="dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-file-earmark-text me-2"></i>
                        Laporan
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('laporan.pembelian.index') }}"><i class="bi bi-file-earmark-bar-graph me-2"></i> Pembelian</a></li>
                        <li><a class="dropdown-item" href="{{ route('laporan.penjualan.index') }}"><i class="bi bi-file-earmark-bar-graph me-2"></i> Penjualan</a></li>
                        <li><a class="dropdown-item" href="{{ route('laporan.produksi.index') }}"><i class="bi bi-file-earmark-bar-graph me-2"></i> Produksi</a></li>
                    </ul>
                </div>
            @endif

            <hr class="sidebar-divider my-2" style="border-color: rgba(255,255,255,0.15);">

            <form method="POST" action="{{ route('logout') }}" class="nav-link">
                @csrf
                <button type="submit" class="btn btn-link text-start p-0 m-0 d-flex align-items-center" style="color: rgba(255,255,255,0.8); text-decoration: none;">
                    <i class="bi bi-box-arrow-right me-2"></i>
                    Keluar
                </button>
            </form>
        </div>
    </div>

    <div class="content">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>