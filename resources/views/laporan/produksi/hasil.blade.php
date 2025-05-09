@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>{{ $title }}</h2>
            <h5 class="text-muted">Kode: {{ $reportCode }}</h5>
        </div>
        <div>
            <a href="{{ route('laporan.produksi.generate', [
                'jenis_laporan' => $jenisLaporan,
                'tanggal' => $tanggal->format($jenisLaporan === 'tahunan' ? 'Y' : ($jenisLaporan === 'bulanan' ? 'Y-m' : 'Y-m-d')),
                'download' => 1
            ]) }}" class="btn btn-success">
                Download CSV
            </a>
            <a href="{{ route('laporan.produksi.index') }}" class="btn btn-secondary ml-2">Kembali</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>No.</th>
                            <th>ID Produksi</th>
                            <th>Tanggal</th>
                            <th>Produk Jadi</th>
                            <th>Jumlah Produksi</th>
                            <th>Bahan Baku</th>
                            <th>Operator</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $produksi)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $produksi->id_produksi }}</td>
                            <td>{{ $produksi->tanggal }}</td>
                            <td>{{ $produksi->produkJadi->nama_produk }}</td>
                            <td>{{ $produksi->jumlah_produksi }}</td>
                            <td>
                                @foreach($produksi->bahanBaku as $bahan)
                                    {{ $bahan->bahan_baku }} ({{ $bahan->pivot->jumlah_bahan_baku }})<br>
                                @endforeach
                            </td>
                            <td>{{ $produksi->user->name ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data produksi</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection