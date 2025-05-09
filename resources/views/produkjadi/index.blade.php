@extends('layouts.app')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h5 class="m-0 font-weight-bold text-primary">Data Produk Jadi</h5>
        <a href="{{ route('produkjadi.create') }}" class="btn btn-primary btn-icon-split">
            <span class="icon text-white-50">
                <i class="bi bi-plus-lg"></i>
            </span>
            <span class="text">Tambah Produk Jadi</span>
        </a>
    </div>
    <div class="card-body">
    <div class="table-responsive">
    <form method="GET" action="{{ route('produkjadi.index') }}" class="mb-3 d-flex justify-content-start align-items-center gap-2">
        <input type="text" name="search" class="form-control w-50" placeholder="Cari nama produk jadi..." value="{{ request('search') }}">
        <button type="submit" class="btn btn-primary">Cari</button>
        <a href="{{ route('produkjadi.index') }}" class="btn btn-secondary">Reset</a>
    </form>
    <table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th width="5%">No.</th>
            <th width="15%">ID Produk Jadi</th>
            <th width="40%">Produk</th>
            <th width="25%">Harga</th>
            <th width="15%">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($data as $produkjadi)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>
                <span class="badge bg-primary bg-opacity-10 text-primary">
                    {{ $produkjadi->id_produk_jadi }}
                </span>
            </td>
            <td>{{ $produkjadi->produk }}</td>
            <td>Rp{{ number_format($produkjadi->harga, 0, ',', '.') }}</td>
            <td class="text-center">
                <a href="{{ route('produkjadi.edit', $produkjadi->id) }}" 
                    class="btn btn-sm btn-warning" title="Edit">
                    <i class="bi bi-pencil"></i>
                </a>
                <form action="{{ route('produkjadi.destroy', $produkjadi->id) }}" 
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
            <td colspan="4" class="text-center">Tidak ada data Produk Jadi</td>
        </tr>
        @endforelse
    </tbody>
</table>
<div class="d-flex justify-content-center mt-3">
    {{ $data->links() }}
</div>
@endsection
