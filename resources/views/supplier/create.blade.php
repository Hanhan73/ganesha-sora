@extends('layouts.app')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h5 class="m-0 font-weight-bold text-primary">Tambah Supplier</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('supplier.store') }}" method="POST">
            @csrf
        <div class="mb-3">
            <label class="form-label">Nama Supplier</label>
            <input type="text" name="supplier" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Alamat</label>
            <textarea name="alamat" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">No Telepon</label>
            <input type="text" name="no_telepon" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Nama Bank</label>
            <input type="text" name="nama_bank" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">No Rekening</label>
            <input type="text" name="no_rekening" class="form-control">
        </div>

        <div class="d-flex justify-content-end">
                <a href="{{ route('supplier.index') }}" class="btn btn-secondary me-2">
                    <i class="bi bi-arrow-left"></i> Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Simpan
                </button>
            </div>
    </form>
</div>
</div>
@endsection
