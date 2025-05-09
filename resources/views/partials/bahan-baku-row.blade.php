<tr>
    <td>
        <x-bahan-baku-select :bahanBakus="$bahanBakus" :selected="$selected ?? null" />
    </td>
    <td>
        <input type="number" name="jumlah_pembelian[]" class="form-control" value="{{ $jumlah ?? '' }}" required>
    </td>
    <td class="text-center">
        <button type="button" class="btn btn-danger remove-row">×</button>
    </td>
</tr>