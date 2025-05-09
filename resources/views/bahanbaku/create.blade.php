@extends('layouts.app')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h5 class="m-0 font-weight-bold text-primary">Tambah Bahan Baku</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('bahanbaku.store') }}" method="POST">
            @csrf

        <div class="mb-3">
            <label class="form-label">Nama Bahan Baku</label>
            <input type="text" name="bahan_baku" class="form-control" required>
        </div>


        <div class="d-flex justify-content-end">
                <a href="{{ route('bahanbaku.index') }}" class="btn btn-secondary me-2">
                    <i class="bi bi-arrow-left"></i> Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Simpan
                </button>
            </div>
        </form>
</div>
@endsection
