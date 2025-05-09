export function setupBahanBakuDynamic() {
    const bahanTable = document.querySelector("#bahan-table tbody");
    const addButton = document.getElementById("add-row");

    if (!addButton) return;

    addButton.addEventListener("click", function() {
        const row = document.createElement("tr");
        row.innerHTML = `@include('partials.bahan-baku-row')`;
        bahanTable.appendChild(row);
        
        // Dispatch custom event to update options
        document.dispatchEvent(new CustomEvent('bahanBakuRowAdded'));
    });

    bahanTable.addEventListener("click", function(e) {
        if (e.target.classList.contains("remove-row")) {
            e.target.closest("tr").remove();
            document.dispatchEvent(new CustomEvent('bahanBakuRowAdded'));
        }
    });
}