@extends('layouts.app')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h5 class="m-0 font-weight-bold text-primary">Tambah Stok Barang</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('stokbarang.store') }}" method="POST">
            @csrf

        <div class="mb-3">
            <label class="form-label">Pilih Barang</label>
            <select id="select-barang" class="form-control" required>
                <option value="">-- Pilih --</option>
                @foreach ($items as $item)
                <option value="{{ $item['id_barang'] }}"
                        data-nama="{{ $item['barang'] }}"
                        data-jenis="{{ $item['jenis_barang'] }}">
                    {{ $item['barang'] }} ({{ ucfirst(str_replace('_', ' ', $item['jenis_barang']) ) }})
                </option>
                @endforeach
            </select>
        </div>

        <input type="hidden" name="id_barang" id="id_barang">
        <input type="hidden" name="barang" id="barang">
        <input type="hidden" name="jenis_barang" id="jenis_barang">

        <div class="mb-3">
            <label class="form-label">Jumlah Stok</label>
            <input type="number" name="stok" class="form-control" required>
        </div>

        <div class="d-flex justify-content-end">
                <a href="{{ route('stokbarang.index') }}" class="btn btn-secondary me-2">
                    <i class="bi bi-arrow-left"></i> Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Simpan
                </button>
            </div>
    </form>
</div>

<script>
    document.getElementById('select-barang').addEventListener('change', function () {
        const selected = this.options[this.selectedIndex];
        document.getElementById('id_barang').value = selected.value;
        document.getElementById('barang').value = selected.getAttribute('data-nama');
        document.getElementById('jenis_barang').value = selected.getAttribute('data-jenis');
    });
</script>
@endsection
