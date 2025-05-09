@extends('layouts.app')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h5 class="m-0 font-weight-bold text-primary">Edit Permintaan Bahan Baku</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('permintaanbahanbaku.update', $permintaanbahanbaku->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="tanggal" class="form-label">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" 
                           value="{{ old('tanggal', $permintaanbahanbaku->tanggal) }}" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Bahan Baku</label>
                <div class="table-responsive">
                    <table class="table table-bordered" id="bahan-table">
                        <thead class="bg-light">
                            <tr>
                                <th width="50%">Bahan Baku</th>
                                <th width="30%">Jumlah</th>
                                <th width="20%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($permintaanbahanbaku->bahanBaku as $bahan)
                            <tr>
                                <td>
                                    <select name="bahan_baku[]" class="form-control bahan-select" required>
                                        <option value="">-- Pilih Bahan Baku --</option>
                                        @foreach($bahanBakus as $b)
                                            <option value="{{ $b->id }}" {{ $b->id == $bahan->id ? 'selected' : '' }}>
                                                {{ $b->bahan_baku }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="jumlah_permintaan[]" class="form-control" 
                                           value="{{ $bahan->pivot->jumlah_permintaan }}" min="1" required>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-danger remove-row">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <button type="button" class="btn btn-sm btn-primary mt-2" id="add-row">
                    <i class="bi bi-plus"></i> Tambah Bahan
                </button>
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('permintaanbahanbaku.index') }}" class="btn btn-secondary me-2">
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
        const bahanTable = document.querySelector("#bahan-table tbody");
        const addButton = document.getElementById("add-row");
        const bahanOptions = @json($bahanBakus);

        function updateDropdownOptions() {
            const selectedIds = Array.from(document.querySelectorAll('.bahan-select'))
                .map(sel => sel.value)
                .filter(val => val !== "");

            document.querySelectorAll('.bahan-select').forEach(currentSelect => {
                const currentValue = currentSelect.value;
                Array.from(currentSelect.options).forEach(option => {
                    if (option.value === "") return;
                    option.disabled = selectedIds.includes(option.value) && option.value !== currentValue;
                });
            });
        }

        function bindEvents(row) {
            const select = row.querySelector('.bahan-select');
            select.addEventListener('change', updateDropdownOptions);
        }

        // Bind existing rows
        bahanTable.querySelectorAll('tr').forEach(row => bindEvents(row));
        updateDropdownOptions();

        addButton.addEventListener("click", function () {
            const row = document.createElement("tr");
            let optionsHtml = '<option value="">-- Pilih Bahan Baku --</option>';
            
            bahanOptions.forEach(b => {
                optionsHtml += `<option value="${b.id}">${b.bahan_baku}</option>`;
            });

            row.innerHTML = `
                <td>
                    <select name="bahan_baku[]" class="form-control bahan-select" required>
                        ${optionsHtml}
                    </select>
                </td>
                <td>
                    <input type="number" name="jumlah_permintaan[]" class="form-control" min="1" required>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-danger remove-row">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            `;
            
            bahanTable.appendChild(row);
            bindEvents(row);
            updateDropdownOptions();
        });

        bahanTable.addEventListener("click", function (e) {
            if (e.target.closest(".remove-row")) {
                e.target.closest("tr").remove();
                updateDropdownOptions();
            }
        });
    });
</script>
@endsection
@endsection