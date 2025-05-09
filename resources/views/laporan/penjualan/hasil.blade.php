@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>{{ $title }}</h2>
            <h5 class="text-muted">Kode: {{ $reportCode }}</h5>
        </div>
        <div>
            <a href="{{ route('laporan.penjualan.generate', [
                'jenis_laporan' => $jenisLaporan,
                'tanggal' => $tanggal->format($jenisLaporan === 'tahunan' ? 'Y' : ($jenisLaporan === 'bulanan' ? 'Y-m' : 'Y-m-d')),
                'download' => 1
            ]) }}" class="btn btn-success">
                Download CSV
            </a>
            <a href="{{ route('laporan.penjualan.index') }}" class="btn btn-secondary ml-2">Kembali</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>No.</th>
                            <th>ID Penjualan</th>
                            <th>Tanggal</th>
                            <th>Pelanggan</th>
                            <th>Produk</th>
                            <th>Total</th>
                            <th>Status Pembayaran</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $penjualan)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $penjualan->id_penjualan_barang }}</td>
                            <td>{{ $penjualan->tanggal }}</td>
                            <td>{{ $penjualan->pelanggan->nama_pelanggan ?? '-' }}</td>
                            <td>
                                @foreach($penjualan->produk as $produk)
                                    {{ $produk->nama_produk }} ({{ $produk->pivot->jumlah_penjualan }})<br>
                                @endforeach
                            </td>
                            <td>Rp{{ number_format($penjualan->total, 0, ',', '.') }}</td>
                            <td>{{ $penjualan->status_pembayaran }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data penjualan</td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if($data->isNotEmpty())
                    <tfoot>
                        <tr>
                            <th colspan="5" class="text-right">Total:</th>
                            <th>Rp{{ number_format($data->sum('total'), 0, ',', '.') }}</th>
                            <th></th>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>
@endsection