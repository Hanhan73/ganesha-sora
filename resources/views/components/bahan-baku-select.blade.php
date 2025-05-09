@props(['bahanBakus', 'name' => 'bahan_baku[]', 'selected' => null])

<select name="{{ $name }}" class="form-control bahan-baku-select" required>
    <option value="">-- Pilih --</option>
    @foreach($bahanBakus as $bahan)
        <option value="{{ $bahan->id }}" 
            {{ $selected == $bahan->id ? 'selected' : '' }}>
            {{ $bahan->bahan_baku }}
        </option>
    @endforeach
</select>

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    // This will work for all selects with class 'bahan-baku-select'
    function updateBahanBakuOptions() {
        const allSelects = document.querySelectorAll('.bahan-baku-select');
        const selectedValues = [];
        
        // Collect all selected values
        allSelects.forEach(select => {
            if (select.value) selectedValues.push(select.value);
        });

        // Update each select's options
        allSelects.forEach(select => {
            const currentValue = select.value;
            Array.from(select.options).forEach(option => {
                if (!option.value) return; // Skip the "Pilih" option
                
                option.disabled = false;
                option.hidden = false;
                
                if (option.value !== currentValue && selectedValues.includes(option.value)) {
                    option.disabled = true;
                    option.hidden = true;
                }
            });
        });
    }

    // Initialize
    updateBahanBakuOptions();
    
    // Add event listeners
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('bahan-baku-select')) {
            updateBahanBakuOptions();
        }
    });
    
    // If you're dynamically adding rows, you might need to trigger this after adding
    document.addEventListener('bahanBakuRowAdded', updateBahanBakuOptions);
});
</script>
@endpush