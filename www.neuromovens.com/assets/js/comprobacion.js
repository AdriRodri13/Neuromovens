document.addEventListener("DOMContentLoaded", function() {
    const form = document.querySelector("form");
    const usernameField = document.getElementById("username");
    const passwordField = document.getElementById("password");
    const errorMessage = document.getElementById("errorMessage");

    form.addEventListener("submit", function(event) {
        // Limpiar el mensaje de error al intentar enviar
        errorMessage.style.display = "none";

        // Validar que ambos campos tengan algún valor
        if (usernameField.value.trim() === "" || passwordField.value.trim() === "") {
            // Mostrar mensaje de error
            errorMessage.style.display = "block";
            errorMessage.textContent = "Por favor, complete ambos campos.";

            // Evitar envío del formulario
            event.preventDefault();
        }
    });
});