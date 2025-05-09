@extends('layouts.app')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h5 class="m-0 font-weight-bold text-primary">Edit Supplier</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('supplier.update', $supplier->id) }}" method="POST">
            @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nama Supplier</label>
            <input type="text" name="supplier" class="form-control" value="{{ $supplier->supplier }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Alamat</label>
            <textarea name="alamat" class="form-control">{{ $supplier->alamat }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">No Telepon</label>
            <input type="text" name="no_telepon" class="form-control" value="{{ $supplier->no_telepon }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Nama Bank</label>
            <input type="text" name="nama_bank" class="form-control" value="{{ $supplier->nama_bank }}">
        </div>

        <div class="mb-3">
            <label class="form-label">No Rekening</label>
            <input type="text" name="no_rekening" class="form-control" value="{{ $supplier->no_rekening }}">
        </div>

        <div class="d-flex justify-content-end">
                <a href="{{ route('supplier.index') }}" class="btn btn-secondary me-2">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Simpan Perubahan
                </button>
            </div>
    </form>
</div>
@endsection
