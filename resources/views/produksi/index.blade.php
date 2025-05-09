@extends('layouts.app')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h5 class="m-0 font-weight-bold text-primary">Data Produksi</h5>
        <a href="{{ route('produksi.create') }}" class="btn btn-primary btn-icon-split">
            <span class="icon text-white-50">
                <i class="bi bi-plus-lg"></i>
            </span>
            <span class="text">Tambah Produksi</span>
        </a>
    </div>
    <div class="card-body">
    <div class="table-responsive">
    <form method="GET" action="{{ route('produksi.index') }}" class="mb-3 d-flex gap-2">
    <input type="text" name="search" class="form-control w-50" placeholder="Cari nama produk..." value="{{ request('search') }}">
    <button type="submit" class="btn btn-primary">Cari</button>
    <a href="{{ route('produksi.index') }}" class="btn btn-secondary">Reset</a>
</form>
    <table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th width="5%">No.</th>
            <th width="15%">ID Produksi</th>
            <th width="10%">Tanggal</th>
            <th width="20%">Produk</th>
            <th>Bahan</th>
            <th width="5%">Jumlah</th>
            <th width="15%">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($data as $produksi)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>
                <span class="badge bg-primary bg-opacity-10 text-primary">
                    {{ $produksi->id_produksi }}
                </span>
            </td>
            <td>{{ date('d M Y', strtotime($produksi->tanggal )) }}</td>
            <td>{{ $produksi->produkJadi->produk }}</td>
            <td>
                @foreach($produksi->bahanBaku as $bahan)
                    <div>{{ $bahan->bahan_baku }} ({{ $bahan->pivot->jumlah_bahan_baku }})</div>
                @endforeach
            </td>
            <td>{{ $produksi->jumlah_produksi }}</td>
            <td class="text-center">
                            <a href="{{ route('produksi.edit', $produksi->id) }}" 
                               class="btn btn-sm btn-warning" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('produksi.destroy', $produksi->id) }}" 
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
                                <span class="text-muted">Tidak ada data produksi</span>
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
