@extends('layouts.app')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h5 class="m-0 font-weight-bold text-primary">Data Penjualan</h5>
        <a href="{{ route('penjualan.create') }}" class="btn btn-primary btn-icon-split">
            <span class="icon text-white-50">
                <i class="bi bi-plus-lg"></i>
            </span>
            <span class="text">Tambah Penjualan</span>
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th width="5%">No.</th>
                        <th width="15%">ID Penjualan</th>
                        <th width="10%">Tanggal</th>
                        <th>Pelanggan</th>
                        <th width="15%">Produk</th>
                        <th width="15%">Total</th>
                        <th width="15%">Status</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $penjualan)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <span class="badge bg-primary bg-opacity-10 text-primary">
                                {{ $penjualan->id_penjualan_barang }}
                            </span>
                        </td>
                        <td>{{ date('d M Y', strtotime($penjualan->tanggal)) }}</td>
                        <td>{{ $penjualan->pelanggan->pelanggan ?? '-' }}</td>
                        <td> @foreach($penjualan->produk as $produk)
                            {{ $produk->produk }} ({{ $produk->pivot->jumlah_penjualan }})<br>
                        @endforeach
                        </td>
                        <td class="text-end">Rp{{ number_format($penjualan->total, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge bg-{{ $penjualan->status_pembayaran == 'Lunas' ? 'success' : 'warning' }}">
                                {{ $penjualan->status_pembayaran }}
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('penjualan.edit', $penjualan->id) }}" 
                               class="btn btn-sm btn-warning" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('penjualan.destroy', $penjualan->id) }}" 
                                  method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" 
                                        onclick="return confirm('Yakin ingin menghapus?')" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <div class="d-flex flex-column align-items-center">
                                <i class="bi bi-receipt fs-1 text-muted mb-2"></i>
                                <span class="text-muted">Tidak ada data penjualan</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="d-flex justify-content-center mt-3">
                {{ $data->links() }}
            </div>
        </div>
    </div>
</div>
@endsection