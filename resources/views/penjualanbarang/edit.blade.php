@extends('layouts.app')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h5 class="m-0 font-weight-bold text-primary">Edit Penjualan</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('penjualan.update', $penjualan->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="tanggal" class="form-label">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" 
                           value="{{ old('tanggal', $penjualan->tanggal) }}" required>
                </div>
                <div class="col-md-6">
                    <label for="pelanggan_id" class="form-label">Pelanggan</label>
                    <select name="pelanggan_id" class="form-control" required>
                        <option value="">-- Pilih Pelanggan --</option>
                        @foreach ($pelanggan as $p)
                            <option value="{{ $p->id }}" {{ $penjualan->pelanggan_id == $p->id ? 'selected' : '' }}>
                                {{ $p->pelanggan }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Produk Dijual</label>
                <div class="table-responsive">
                    <table class="table table-bordered" id="produk-table">
                        <thead class="bg-light">
                            <tr>
                                <th width="40%">Produk</th>
                                <th width="20%">Harga</th>
                                <th width="20%">Jumlah</th>
                                <th width="20%">Subtotal</th>
                                <th width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($penjualan->produk as $i => $prod)
                            <tr>
                                <td>
                                    <select name="produk_jadi[]" class="form-control produk-select" required>
                                        <option value="">-- Pilih Produk --</option>
                                        @foreach($produk as $p)
                                            <option value="{{ $p->id }}" data-harga="{{ $p->harga }}"
                                                {{ $p->id == $prod->id ? 'selected' : '' }}>
                                                {{ $p->produk }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="harga[]" class="form-control harga-input"
                                        value="{{ $prod->pivot->harga }}" readonly>
                                </td>
                                <td>
                                    <input type="number" name="jumlah_penjualan[]" class="form-control jumlah-input"
                                        value="{{ $prod->pivot->jumlah_penjualan }}" min="1" required>
                                </td>
                                <td>
                                    <input type="number" class="form-control subtotal-input" 
                                           value="{{ $prod->pivot->harga * $prod->pivot->jumlah_penjualan }}" readonly>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-danger remove-row">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end fw-bold">Total</td>
                                <td>
                                    <input type="number" name="total" id="total" class="form-control"
                                        value="{{ $penjualan->total }}" readonly>
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <button type="button" class="btn btn-sm btn-primary mt-2" id="add-row">
                    <i class="bi bi-plus"></i> Tambah Produk
                </button>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="status_pembayaran" class="form-label">Status Pembayaran</label>
                    <select name="status_pembayaran" class="form-control" required>
                        <option value="Belum Lunas" {{ $penjualan->status_pembayaran == 'Belum Lunas' ? 'selected' : '' }}>
                            Belum Lunas
                        </option>
                        <option value="Lunas" {{ $penjualan->status_pembayaran == 'Lunas' ? 'selected' : '' }}>
                            Lunas
                        </option>
                    </select>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('penjualan.index') }}" class="btn btn-secondary me-2">
                    <i class="bi bi-arrow-left"></i> Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const produkTable = document.querySelector("#produk-table tbody");
        const addButton = document.getElementById("add-row");
        const produkOptions = @json($produk);

        function calculateRow(row) {
            const harga = parseFloat(row.querySelector('.harga-input').value) || 0;
            const jumlah = parseFloat(row.querySelector('.jumlah-input').value) || 0;
            const subtotal = harga * jumlah;
            row.querySelector('.subtotal-input').value = subtotal;
            return subtotal;
        }

        function updateTotal() {
            let total = 0;
            document.querySelectorAll('#produk-table tbody tr').forEach(row => {
                total += calculateRow(row);
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
                    option.disabled = selectedIds.includes(option.value) && option.value !== currentValue;
                });
            });
        }

        function bindEvents(row) {
            const select = row.querySelector('.produk-select');
            const hargaInput = row.querySelector('.harga-input');
            const jumlahInput = row.querySelector('.jumlah-input');

            select.addEventListener('change', function () {
                const selectedOption = this.options[this.selectedIndex];
                hargaInput.value = selectedOption.dataset.harga || 0;
                updateTotal();
                updateDropdownOptions();
            });

            jumlahInput.addEventListener('input', updateTotal);
        }

        // Bind existing rows
        produkTable.querySelectorAll('tr').forEach(row => bindEvents(row));
        updateDropdownOptions();

        addButton.addEventListener("click", function () {
            const row = document.createElement("tr");
            let optionsHtml = '<option value="">-- Pilih Produk --</option>';
            
            produkOptions.forEach(p => {
                optionsHtml += `<option value="${p.id}" data-harga="${p.harga}">${p.produk}</option>`;
            });

            row.innerHTML = `
                <td>
                    <select name="produk_jadi[]" class="form-control produk-select" required>
                        ${optionsHtml}
                    </select>
                </td>
                <td>
                    <input type="number" name="harga[]" class="form-control harga-input" readonly>
                </td>
                <td>
                    <input type="number" name="jumlah_penjualan[]" class="form-control jumlah-input" min="1" required>
                </td>
                <td>
                    <input type="number" class="form-control subtotal-input" readonly>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-danger remove-row">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            `;
            
            produkTable.appendChild(row);
            bindEvents(row);
            updateDropdownOptions();
        });

        produkTable.addEventListener("click", function (e) {
            if (e.target.closest(".remove-row")) {
                e.target.closest("tr").remove();
                updateTotal();
                updateDropdownOptions();
            }
        });

        // Initialize totals
        updateTotal();
    });
</script>
@endsection
@endsection