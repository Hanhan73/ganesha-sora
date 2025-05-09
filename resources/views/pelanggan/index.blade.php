@extends('layouts.app')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h5 class="m-0 font-weight-bold text-primary">Data Pelanggan</h5>
        <a href="{{ route('pelanggan.create') }}" class="btn btn-primary btn-icon-split">
            <span class="icon text-white-50">
                <i class="bi bi-plus-lg"></i>
            </span>
            <span class="text">Tambah Pelanggan</span>
        </a>
    </div>
    <div class="card-body">
    <div class="table-responsive">
    <form method="GET" action="{{ route('pelanggan.index') }}" class="mb-3 d-flex justify-content-start align-items-center gap-2">
        <input type="text" name="search" class="form-control w-50" placeholder="Cari nama pelanggan..." value="{{ request('search') }}">
        <button type="submit" class="btn btn-primary">Cari</button>
        <a href="{{ route('pelanggan.index') }}" class="btn btn-secondary">Reset</a>
    </form>
    <table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>No.</th>
            <th>ID Pelanggan</th>
            <th>Nama</th>
            <th>Alamat</th>
            <th>No. Telepon</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($data as $pelanggan)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>
            <span class="badge bg-primary bg-opacity-10 text-primary">
                {{ $pelanggan->id_pelanggan }}
            </span>  
            </td>
            <td>{{ $pelanggan->pelanggan }}</td>
            <td>{{ $pelanggan->alamat }}</td>
            <td>{{ $pelanggan->no_telepon }}</td>
            <td class="text-center">
                <a href="{{ route('pelanggan.edit', $pelanggan->id) }}" 
                    class="btn btn-sm btn-warning" title="Edit">
                    <i class="bi bi-pencil"></i>
                </a>
                <form action="{{ route('pelanggan.destroy', $pelanggan->id) }}" 
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
            <td colspan="4" class="text-center">Tidak ada data pelanggan</td>
        </tr>
        @endforelse
    </tbody>
</table>
<div class="d-flex justify-content-center mt-3">
    {{ $data->links() }}
</div>
@endsection
