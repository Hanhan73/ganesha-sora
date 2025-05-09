@extends('layouts.app')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h5 class="m-0 font-weight-bold text-primary">Edit Pembelian</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('produkjadi.update', $produkjadi->id) }}" method="POST">
            @csrf
        @method('PUT')


        <div class="mb-3">
            <label class="form-label">Nama Produk</label>
            <input type="text" name="produk" class="form-control" 
                   value="{{ old('produk', $produkjadi->produk) }}" required>
            @error('produk')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Harga</label>
            <input type="number" name="harga" class="form-control" 
                   value="{{ old('harga', $produkjadi->harga) }}" required>
            @error('harga')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex justify-content-end">
                <a href="{{ route('produkjadi.index') }}" class="btn btn-secondary me-2">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Simpan Perubahan
                </button>
            </div>
    </form>
</div>
@endsection