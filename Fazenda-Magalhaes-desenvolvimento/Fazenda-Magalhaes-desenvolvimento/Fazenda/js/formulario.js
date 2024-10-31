document.addEventListener("DOMContentLoaded", function () {
    const loteContainer = document.getElementById("loteContainer");
    const addLoteButton = document.getElementById("addLoteButton");

    addLoteButton.addEventListener("click", function () {
        // Criando novo elemento de lote
        const loteDiv = document.createElement("div");
        loteDiv.classList.add("lote");

        // Adicionando campo de número de animais
        const labelAnimais = document.createElement("label");
        labelAnimais.textContent = "Número de Animais no Lote:";
        const inputAnimais = document.createElement("input");
        inputAnimais.type = "number";
        inputAnimais.name = "animaisLote[]";
        inputAnimais.required = true;

        // Adicionando os elementos à div do lote
        loteDiv.appendChild(labelAnimais);
        loteDiv.appendChild(inputAnimais);

        // Inserindo o novo lote acima do botão "Adicionar Novo Lote"
        loteContainer.insertBefore(loteDiv, addLoteButton);
    });
});