@extends('layouts.app')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h5 class="m-0 font-weight-bold text-primary">Edit Bahan Baku</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('bahanbaku.update', $bahanbaku->id) }}" method="POST">
            @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nama Bahan Baku</label>
            <input type="text" name="bahan_baku" class="form-control" value="{{ $bahanbaku->bahan_baku }}" required>
        </div>

        <div class="d-flex justify-content-end">
                <a href="{{ route('bahanbaku.index') }}" class="btn btn-secondary me-2">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Simpan Perubahan
                </button>
            </div>
    </form>
</div>
@endsection
