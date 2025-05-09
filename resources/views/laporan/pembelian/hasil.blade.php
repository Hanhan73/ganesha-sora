@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>{{ $title }}</h2>
            <h5 class="text-muted">Kode: {{ $reportCode }}</h5>
        </div>
        <div>
            <a href="{{ route('laporan.pembelian.generate', [
                'jenis_laporan' => $jenisLaporan,
                'tanggal' => $jenisLaporan === 'tahunan' ? $tanggal->format('Y-01-01') : ($jenisLaporan === 'bulanan' ? $tanggal->format('Y-m-01') : $tanggal->format('Y-m-d')),
                'download' => 1
            ]) }}" class="btn btn-success">
                Download CSV
            </a>
            <a href="{{ route('laporan.pembelian.index') }}" class="btn btn-secondary ml-2">Kembali</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>No.</th>
                            <th>ID Pembelian</th>
                            <th>Tanggal</th>
                            <th>Supplier</th>
                            <th>Bahan Baku</th>
                            <th>Total</th>
                            <th>Status Pembayaran</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $pembelian)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $pembelian->id_pembelian }}</td>
                            <td>{{ \Carbon\Carbon::parse($pembelian->tanggal)->format('d/m/Y') }}</td>
                            <td>{{ $pembelian->suppliers->supplier ?? '-' }}</td>
                            <td>
                                @if($pembelian->bahanBaku->isEmpty())
                                    <span class="text-muted">Tidak ada data bahan baku</span>
                                @else
                                    @foreach($pembelian->bahanBaku as $bahan)
                                        {{ $bahan->bahan_baku }} ({{ $bahan->pivot->jumlah_pembelian }})<br>
                                    @endforeach
                                @endif
                            </td>
                            <td>Rp{{ number_format($pembelian->total, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge bg-{{ $pembelian->status_pembayaran == 'Lunas' ? 'success' : 'warning' }}">
                                    {{ $pembelian->status_pembayaran }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">
                                <div class="alert alert-info mb-0">
                                    Tidak ada data pembelian untuk periode {{ $jenisLaporan }} yang dipilih
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if($data->isNotEmpty())
                    <tfoot>
                        <tr class="table-secondary">
                            <th colspan="5" class="text-end">Total:</th>
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