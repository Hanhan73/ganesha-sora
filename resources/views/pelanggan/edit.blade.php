@extends('layouts.app')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h5 class="m-0 font-weight-bold text-primary">Edit Pembelian</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('pelanggan.update', $pelanggan->id) }}" method="POST">
            @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nama Pelanggan</label>
            <input type="text" name="pelanggan" class="form-control" value="{{ $pelanggan->pelanggan }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Alamat</label>
            <textarea name="alamat" class="form-control">{{ $pelanggan->alamat }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">No Telepon</label>
            <input type="number" name="no_telepon" class="form-control" value="{{ $pelanggan->no_telepon }}">
        </div>

        </div>

        <div class="d-flex justify-content-end">
                <a href="{{ route('pelanggan.index') }}" class="btn btn-secondary me-2">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Simpan Perubahan
                </button>
            </div>
    </form>
</div>
@endsection
