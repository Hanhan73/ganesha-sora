@extends('layouts.app')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h5 class="m-0 font-weight-bold text-primary">Edit Pembelian</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('stokbarang.update', $stokbarang->id) }}" method="POST">
            @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Pilih Barang</label>
            <select id="select-barang" class="form-control" required>
                <option value="">-- Pilih --</option>
                @foreach ($items as $item)
                <option value="{{ $item['id_barang'] }}"
                        data-nama="{{ $item['barang'] }}"
                        data-jenis="{{ $item['jenis_barang'] }}"
                        {{ $stokbarang->id_barang == $item['id_barang'] ? 'selected' : '' }}>
                    {{ $item['barang'] }} ({{ ucfirst(str_replace('_', ' ', $item['jenis_barang']) ) }})
                </option>
                @endforeach
            </select>
        </div>

        <input type="hidden" name="id_barang" id="id_barang" value="{{ $stokbarang->id_barang }}">
        <input type="hidden" name="barang" id="barang" value="{{ $stokbarang->barang }}">
        <input type="hidden" name="jenis_barang" id="jenis_barang" value="{{ $stokbarang->jenis_barang }}">

        <div class="mb-3">
            <label class="form-label">Jumlah Stok</label>
            <input type="number" name="stok" class="form-control" value="{{ $stokbarang->stok }}" required>
        </div>

        <div class="d-flex justify-content-end">
                <a href="{{ route('stokbarang.index') }}" class="btn btn-secondary me-2">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Simpan Perubahan
                </button>
            </div>
    </form>
</div>

<script>
    const selectBarang = document.getElementById('select-barang');
    const idBarangInput = document.getElementById('id_barang');
    const namaBarangInput = document.getElementById('barang');
    const jenisBarangInput = document.getElementById('jenis_barang');

    function updateHiddenFields() {
        const selected = selectBarang.options[selectBarang.selectedIndex];
        idBarangInput.value = selected.value;
        namaBarangInput.value = selected.getAttribute('data-nama');
        jenisBarangInput.value = selected.getAttribute('data-jenis');
    }

    // Trigger saat pilihan berubah
    selectBarang.addEventListener('change', updateHiddenFields);

    // Trigger awal saat halaman dimuat (untuk memastikan data terisi)
    window.addEventListener('DOMContentLoaded', updateHiddenFields);
</script>
@endsection
