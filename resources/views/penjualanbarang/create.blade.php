@extends('layouts.app')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h5 class="m-0 font-weight-bold text-primary">Tambah Penjualan</h5>
    </div>
    <script>
        const produkList = @json($produk);
    </script>
    <div class="card-body">
        <form action="{{ route('penjualan.store') }}" method="POST">
            @csrf
        <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal</label>
            <input type="date" name="tanggal" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="pelanggan_id" class="form-label">Pelanggan</label>
            <select name="pelanggan_id" class="form-control" required>
                <option value="">-- Pilih Pelanggan --</option>
                @foreach ($pelanggan as $p)
                    <option value="{{ $p->id }}">{{ $p->pelanggan }}</option>
                @endforeach
            </select>
        </div>

        <label>Produk Dijual</label>
        <table class="table table-bordered" id="produk-table">
            <thead>
                <tr>
                    <th>Nama Produk</th>
                    <th>Harga Satuan</th>
                    <th>Jumlah</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <select name="produk_jadi[]" class="form-control produk-select" required>
                            <option value="">-- Pilih Produk --</option>
                            @foreach($produk as $p)
                                <option value="{{ $p->id }}" data-harga="{{ $p->harga }}">{{ $p->produk }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="number" name="harga[]" class="form-control harga-input" readonly>
                    </td>
                    <td>
                        <input type="number" name="jumlah_penjualan[]" class="form-control jumlah-input" required>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger remove-row">×</button>
                    </td>
                </tr>
            </tbody>
        </table>
        <button type="button" class="btn btn-secondary mb-3" id="add-row">+ Tambah Produk</button>

        <div class="mb-3">
            <label for="total" class="form-label">Total Pembayaran</label>
            <input type="number" name="total" id="total" class="form-control" readonly>
        </div>

        <div class="mb-3">
            <label for="status_pembayaran" class="form-label">Status Pembayaran</label>
            <select name="status_pembayaran" class="form-control" required>
                <option value="Belum Lunas">Belum Lunas</option>
                <option value="Lunas">Lunas</option>
            </select>
        </div>

        <script type="text/template" id="produk-options">
            <option value="">-- Pilih Produk --</option>
            @foreach($produk as $p)
                <option value="{{ $p->id }}" data-harga="{{ $p->harga }}">{{ $p->produk }}</option>
            @endforeach
        </script>

        <button type="submit" class="btn btn-primary">Simpan</button>
        @if($errors->any())
            <div class="alert alert-danger mt-3">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <a href="{{ route('penjualan.index') }}" class="btn btn-secondary mt-2">Batal</a>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function updateTotal() {
        let total = 0;
        document.querySelectorAll('#produk-table tbody tr').forEach(row => {
            const harga = parseFloat(row.querySelector('.harga-input').value) || 0;
            const jumlah = parseFloat(row.querySelector('.jumlah-input').value) || 0;
            total += harga * jumlah;
        });
        document.getElementById('total').value = total;
    }

    function updateDropdownOptions() {
        const selectedIds = Array.from(document.querySelectorAll('.produk-select'))
            .map(sel => sel.value)
            .filter(val => val !== "");

        document.querySelectorAll('.produk-select').forEach(currentSelect => {
            const currentValue = currentSelect.value;
            Array.from(currentSelect.options).forEach(option => {
                if (option.value === "") return;
                if (option.value === currentValue || !selectedIds.includes(option.value)) {
                    option.hidden = false;
                } else {
                    option.hidden = true;
                }
            });
        });
    }

    function bindEvents(row) {
        const select = row.querySelector('.produk-select');
        const hargaInput = row.querySelector('.harga-input');
        const jumlahInput = row.querySelector('.jumlah-input');

        select.addEventListener('change', function () {
            const selected = this.options[this.selectedIndex];
            hargaInput.value = selected.dataset.harga || 0;
            updateTotal();
            updateDropdownOptions();
        });

        jumlahInput.addEventListener('input', updateTotal);
    }

    document.addEventListener("DOMContentLoaded", function () {
        const produkTable = document.querySelector("#produk-table tbody");
        const addButton = document.getElementById("add-row");

        bindEvents(produkTable.querySelector('tr'));

        addButton.addEventListener("click", function () {
            const row = document.createElement("tr");
            let optionHtml = '<option value="">-- Pilih Produk --</option>';
            produkList.forEach(p => {
                optionHtml += `<option value="${p.id}" data-harga="${p.harga}">${p.produk}</option>`;
            });

            row.innerHTML = `
                <td>
                    <select name="produk_jadi[]" class="form-control produk-select" required>
                        ${optionHtml}
                    </select>
                </td>
                <td>
                    <input type="number" name="harga[]" class="form-control harga-input" readonly>
                </td>
                <td>
                    <input type="number" name="jumlah_penjualan[]" class="form-control jumlah-input" required>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-danger remove-row">×</button>
                </td>
            `;
            produkTable.appendChild(row);
            bindEvents(row);
            updateDropdownOptions();
        });

        produkTable.addEventListener("click", function (e) {
            if (e.target.classList.contains("remove-row")) {
                e.target.closest("tr").remove();
                updateTotal();
                updateDropdownOptions();
            }
        });
    });
</script>
@endsection
