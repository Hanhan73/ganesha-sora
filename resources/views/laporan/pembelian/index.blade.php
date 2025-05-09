@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Laporan Pembelian</h2>
    <form method="GET" action="{{ route('laporan.pembelian.generate') }}" class="mb-4">
        <div class="mb-3">
            <label for="jenis_laporan" class="form-label">Jenis Laporan</label>
            <select class="form-control" id="jenis_laporan" name="jenis_laporan" required>
                <option value="harian">Harian</option>
                <option value="bulanan">Bulanan</option>
                <option value="tahunan">Tahunan</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal</label>
            <input type="date" class="form-control" id="tanggal" name="tanggal" required>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Generate</button>
            <button type="submit" name="download" value="1" class="btn btn-success">Download CSV</button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const jenisLaporan = document.getElementById('jenis_laporan');
        const tanggalInput = document.getElementById('tanggal');

        const today = new Date().toISOString().split('T')[0];
        tanggalInput.value = today;

        jenisLaporan.addEventListener('change', function() {
            if (this.value === 'bulanan') {
                tanggalInput.type = 'month';
            } else if (this.value === 'tahunan') {
                tanggalInput.type = 'number';
                tanggalInput.min = 2000;
                tanggalInput.max = new Date().getFullYear();
                tanggalInput.value = new Date().getFullYear();
            } else {
                tanggalInput.type = 'date';
                tanggalInput.value = today;
            }
        });
    });
</script>
@endsection
