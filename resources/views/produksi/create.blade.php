@extends('layouts.app')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h5 class="m-0 font-weight-bold text-primary">Tambah Produksi</h5>
    </div>
    <div class="card-body">
    <form action="{{ route('produksi.store') }}" method="POST">
        @csrf
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="tanggal" class="form-label">Tanggal</label>
                <input type="date" name="tanggal" class="form-control" required>
            </div>
        </div>

        <div class="col-md-6">
        <label for ="produk_id" class="form-label">Produk Jadi</label>
            <select name="produk_id" class="form-control" required>
                <option value="">-- Pilih --</option>
                @foreach($produkJadi as $produk)
                    <option value="{{ $produk->id }}">{{ $produk->produk }}</option>
                @endforeach
            </select>
        </div>

        <div class="row mb-3">
        <div class="col-md-6">
            <label for="jumlah_produksi" class="form-label">Jumlah Produksi</label>
            <input type="number" name="jumlah_produksi" class="form-control" required>
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
                            <tr>
                                <td>
                                    <select name="bahan_baku[]" class="form-control bahan-select" required>
                                        <option value="">-- Pilih Bahan Baku --</option>
                                        @foreach($bahanBaku as $bahan)
                                            <option value="{{ $bahan->id }}">{{ $bahan->bahan_baku }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="jumlah_bahan[]" class="form-control" min="1" required>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-danger remove-row">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <button type="button" class="btn btn-sm btn-primary mt-2" id="add-row">
                    <i class="bi bi-plus"></i> Tambah Bahan
                </button>
            </div>
</div>
<script type="text/template" id="bahan-options">
    <option value="">-- Pilih --</option>
    @foreach($bahanBaku as $bahan)
        <option value="{{ $bahan->id }}">{{ $bahan->bahan_baku }}</option>
    @endforeach
</script>


<div class="d-flex justify-content-end">
                <a href="{{ route('produksi.index') }}" class="btn btn-secondary me-2">
                    <i class="bi bi-arrow-left"></i> Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Simpan
                </button>
            </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const bahanTable = document.querySelector("#bahan-table tbody");
        const addButton = document.getElementById("add-row");
        const optionsHtml = document.getElementById("bahan-options").innerHTML;

        function updateSelectOptions() {
            const allSelects = document.querySelectorAll('select[name="bahan_baku[]"]');
            const selectedValues = [];
            
            allSelects.forEach(select => {
                if (select.value) {
                    selectedValues.push(select.value);
                }
            });

            allSelects.forEach(select => {
                const currentValue = select.value;
                const options = select.options;
                
                for (let i = 0; i < options.length; i++) {
                    options[i].disabled = false;
                    options[i].hidden = false;
                }
                
                if (currentValue) {
                    for (let i = 0; i < options.length; i++) {
                        if (options[i].value && options[i].value !== currentValue && 
                            selectedValues.includes(options[i].value)) {
                            options[i].disabled = true;
                            options[i].hidden = true;
                        }
                    }
                } else {
                    for (let i = 0; i < options.length; i++) {
                        if (options[i].value && selectedValues.includes(options[i].value)) {
                            options[i].disabled = true;
                            options[i].hidden = true;
                        }
                    }
                }
            });
        }

        updateSelectOptions();

        addButton.addEventListener("click", function () {
            const row = document.createElement("tr");
            row.innerHTML = `
                <td>
                    <select name="bahan_baku[]" class="form-control" required>
                        ${optionsHtml}
                    </select>
                </td>
                <td>
                    <input type="number" name="jumlah_bahan[]" class="form-control" required>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-danger remove-row">×</button>
                </td>
            `;
            bahanTable.appendChild(row);
            
            const newSelect = row.querySelector('select');
            newSelect.addEventListener('change', updateSelectOptions);
            
            updateSelectOptions();
        });

        bahanTable.addEventListener("click", function (e) {
            if (e.target.classList.contains("remove-row")) {
                e.target.closest("tr").remove();
                updateSelectOptions();
            }
        });

        document.querySelectorAll('select[name="bahan_baku[]"]').forEach(select => {
            select.addEventListener('change', updateSelectOptions);
        });
    });
</script>




