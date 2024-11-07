document.addEventListener("DOMContentLoaded", function () {
    const loteContainer = document.getElementById("loteContainer");
    const addLoteButton = document.getElementById("addLoteButton");

    addLoteButton.addEventListener("click", function () {
        const loteDiv = document.createElement("div");
        loteDiv.classList.add("lote");

        const labelAnimais = document.createElement("label");
        labelAnimais.textContent = "Número de Animais no Lote:";
        const inputAnimais = document.createElement("input");
        inputAnimais.type = "number";
        inputAnimais.name = "animaisLote[]";
        inputAnimais.required = true;

        loteDiv.appendChild(labelAnimais);
        loteDiv.appendChild(inputAnimais);

        loteContainer.insertBefore(loteDiv, addLoteButton);
    });
});