@extends('layouts.app')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h5 class="m-0 font-weight-bold text-primary">Data Stok Barang</h5>
        <a href="{{ route('stokbarang.create') }}" class="btn btn-primary btn-icon-split">
            <span class="icon text-white-50">
                <i class="bi bi-plus-lg"></i>
            </span>
            <span class="text">Tambah Stok Barang</span>
        </a>
    </div>
    <div class="card-body">
    <div class="table-responsive">
    <form method="GET" action="{{ route('stokbarang.index') }}" class="mb-3 d-flex justify-content-start align-items-center gap-2">
    <input type="text" name="search" class="form-control w-50" placeholder="Cari nama stok barang..." value="{{ request('search') }}">
    
    <select name="filter_jenis" class="form-select">
        <option value="">Semua Jenis</option>
        <option value="bahan_baku" {{ request('filter_jenis') == 'bahan_baku' ? 'selected' : '' }}>Bahan Baku</option>
        <option value="produk_jadi" {{ request('filter_jenis') == 'produk_jadi' ? 'selected' : '' }}>Produk Jadi</option>
    </select>

    <button type="submit" class="btn btn-primary">Cari</button>
    <a href="{{ route('stokbarang.index') }}" class="btn btn-secondary">Reset</a>
</form>
    <table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th width="5%">No</th>
            <th width="15%">ID Barang</th>
            <th>Nama Barang</th>
            <th>Jenis</th>
            <th>Stok</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($data as $stok)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>
                <span class="badge bg-primary bg-opacity-10 text-primary">
                    {{ $stok->id_barang }}
                </span>
            </td>
            <td>{{ $stok->barang }}</td>
            <td>{{ ucfirst(str_replace('_', ' ', $stok->jenis_barang)) }}</td>
            
            <td>
                <span class="badge bg-success bg-opacity-10 text-success">
                    {{ $stok->stok }}
                </span>
            </td>
            <td class="text-center">
                <a href="{{ route('stokbarang.edit', $stok->id) }}" 
                    class="btn btn-sm btn-warning" title="Edit">
                    <i class="bi bi-pencil"></i>
                </a>
                <form action="{{ route('stokbarang.destroy', $stok->id) }}" 
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
            <td colspan="6" class="text-center">Tidak ada data</td>
        </tr>
        @endforelse
    </tbody>
</table>
<div class="d-flex justify-content-center mt-3">
    {{ $data->links() }}
</div>
@endsection
