@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header Dashboard -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-gradient">Selamat Datang, {{ auth()->user()->username }}</h2>
            <p class="text-muted mb-0">Dashboard Overview</p>
        </div>
        <div class="text-end">
            <div class="text-primary fw-bold">{{ now()->translatedFormat('l, d F Y') }}</div>
            <div class="text-muted small">{{ now()->format('H:i') }}</div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        @if(auth()->user()->hasRole('admin'))
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-primary border-4 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase text-muted fw-bold small">Total Bahan Baku</h6>
                            <h3 class="mb-0 fw-bold">{{ $totalBahanBaku }}</h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <i class="fas fa-boxes fa-lg text-primary"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('bahanbaku.index') }}" class="small text-primary text-decoration-none">Lihat detail <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-success border-4 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase text-muted fw-bold small">Total Produk Jadi</h6>
                            <h3 class="mb-0 fw-bold">{{ $totalProdukJadi }}</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="fas fa-box-open fa-lg text-success"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('produkjadi.index') }}" class="small text-success text-decoration-none">Lihat detail <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-info border-4 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase text-muted fw-bold small">Total Pelanggan</h6>
                            <h3 class="mb-0 fw-bold">{{ $totalPelanggan }}</h3>
                        </div>
                        <div class="bg-info bg-opacity-10 p-3 rounded">
                            <i class="fas fa-users fa-lg text-info"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('pelanggan.index') }}" class="small text-info text-decoration-none">Lihat detail <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-warning border-4 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase text-muted fw-bold small">Total Supplier</h6>
                            <h3 class="mb-0 fw-bold">{{ $totalSupplier }}</h3>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded">
                            <i class="fas fa-truck fa-lg text-warning"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('supplier.index') }}" class="small text-warning text-decoration-none">Lihat detail <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('direktur'))
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-danger border-4 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase text-muted fw-bold small">Total Pembelian</h6>
                            <h3 class="mb-0 fw-bold">Rp{{ number_format($totalPembelian, 0, ',', '.') }}</h3>
                        </div>
                        <div class="bg-danger bg-opacity-10 p-3 rounded">
                            <i class="fas fa-shopping-cart fa-lg text-danger"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('pembelian.index') }}" class="small text-danger text-decoration-none">Lihat detail <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-purple border-4 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase text-muted fw-bold small">Total Penjualan</h6>
                            <h3 class="mb-0 fw-bold">Rp{{ number_format($totalPenjualan, 0, ',', '.') }}</h3>
                        </div>
                        <div class="bg-purple bg-opacity-10 p-3 rounded">
                            <i class="fas fa-cash-register fa-lg text-purple"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('penjualan.index') }}" class="small text-purple text-decoration-none">Lihat detail <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="row">

        @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('gudang'))
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                    <h6 class="m-0 font-weight-bold text-warning">Stok Terendah</h6>
                    <a href="{{ route('stokbarang.index') }}" class="btn btn-sm btn-outline-warning">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Nama Barang</th>
                                    <th>Stok</th>
                                    <th>Jenis</th>
                                    <th class="pe-4">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stokMinimumList as $stok)
                                <tr>
                                    <td class="ps-4">{{ $stok->barang }}</td>
                                    <td>
                                        <span class="badge bg-{{ $stok->stok <= 5 ? 'danger' : 'success' }}-subtle text-{{ $stok->stok <= 5 ? 'danger' : 'success' }}">
                                            {{ $stok->stok }}
                                        </span>
                                    </td>
                                    <td>{{ $stok->jenis_barang }}</td>
                                    <td class="pe-4">
                                        @if($stok->stok <= 5)
                                        <span class="badge bg-danger text-white">Perlu Restock</span>
                                        @else
                                        <span class="badge bg-success text-white">Aman</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">Tidak ada stok minimum saat ini</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('gudang') || auth()->user()->hasRole('produksi'))
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                    <h6 class="m-0 font-weight-bold text-info">Permintaan Bahan Baku Terbaru</h6>
                    <a href="{{ route('permintaanbahanbaku.index') }}" class="btn btn-sm btn-outline-info">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">ID Permintaan</th>
                                    <th>Tanggal</th>
                                    <th>Bahan Baku</th>
                                    <th class="pe-4">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($permintaanTerbaru as $permintaan)
                                <tr>
                                    <td class="ps-4">{{ $permintaan->id_permintaan }}</td>
                                    <td>{{ \Carbon\Carbon::parse($permintaan->tanggal)->format('d M Y') }}</td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            @foreach($permintaan->bahanBaku as $bb)
                                            <span class="badge bg-info-subtle text-info mb-1">{{ $bb->bahan_baku }}</span>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="pe-4">
                                        @foreach($permintaan->bahanBaku as $bb)
                                        <span class="badge bg-primary-subtle text-primary mb-1">{{ $bb->pivot->jumlah_permintaan }}</span>
                                        @endforeach
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">Belum ada permintaan</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>


<style>
    .text-gradient {
        background: linear-gradient(90deg, #4e73df 0%, #224abe 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        display: inline-block;
    }
    .border-purple {
        border-left-color: #6f42c1 !important;
    }
    .bg-purple {
        background-color: #6f42c1 !important;
    }
    .card {
        border-radius: 0.5rem;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.1) !important;
    }
    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }
</style>
@endsection