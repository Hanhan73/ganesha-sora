@extends('layouts.app')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h5 class="m-0 font-weight-bold text-primary">Data Pembelian</h5>
        <a href="{{ route('pembelian.create') }}" class="btn btn-primary btn-icon-split">
            <span class="icon text-white-50">
                <i class="bi bi-plus-lg"></i>
            </span>
            <span class="text">Tambah Pembelian</span>
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th width="5%">No.</th>
                        <th width="15%">ID Pembelian</th>
                        <th width="10%">Tanggal</th>
                        <th>Supplier</th>
                        <th width="15%">Bahan Baku</th>
                        <th width="15%">Total</th>
                        <th width="5%">Status</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $pembelian)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                        <span class="badge bg-primary bg-opacity-10 text-primary">
                                {{ $pembelian->id_pembelian }}
                            </span>
                        </td>
                        <td>{{ date('d M Y', strtotime($pembelian->tanggal )) }}</td>
                        <td>{{ $pembelian->suppliers->supplier ?? '-' }}</td>
                        <td>
                            @foreach($pembelian->bahanBaku as $bahan)
                                {{ $bahan->bahan_baku }} ({{ $bahan->pivot->jumlah_pembelian }})<br>
                            @endforeach
                        </td>
                        <td class="text-end">Rp{{ number_format($pembelian->total, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge bg-{{ $pembelian->status_pembayaran == 'Lunas' ? 'success' : 'warning' }}">
                                {{ $pembelian->status_pembayaran }}
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('pembelian.edit', $pembelian->id) }}" 
                               class="btn btn-sm btn-warning" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('pembelian.destroy', $pembelian->id) }}" 
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
                                <span class="text-muted">Tidak ada data pembelian</span>
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
