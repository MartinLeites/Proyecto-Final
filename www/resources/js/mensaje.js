document.addEventListener("DOMContentLoaded", function () {
    const formulario = document.querySelector(".editar-perfil-formulario");
    const inputMail = document.getElementById("Mail");

    if (!formulario || !inputMail) return;

    const mailOriginal = inputMail.dataset.mailOriginal;

    formulario.addEventListener("submit", function (e) {
        const nuevoMail = (inputMail.value || "").trim();

        // Si no hay nada escrito, no hay cambio de mail → seguir normal
        if (!nuevoMail) {
            return;
        }

        // Si es distinto al original, pedimos confirmación
        if (nuevoMail !== mailOriginal) {
            const mensaje =
                "Vas a cambiar el correo electrónico de tu cuenta.\n\n" +
                "Al confirmar esta acción:\n" +
                "• Se cerrará tu sesión actual.\n" +
                "• Se reiniciará el código de autenticación en dos pasos (2FA).\n\n" +
                "¿Confirmas que quieres continuar?";

            if (!window.confirm(mensaje)) {
                e.preventDefault();
            }
        }
    });
});
