document.addEventListener("DOMContentLoaded",function(){const n=document.querySelector(".editar-perfil-formulario"),e=document.getElementById("Mail");if(!n||!e)return;const t=e.dataset.mailOriginal;n.addEventListener("submit",function(a){const i=(e.value||"").trim();i&&i!==t&&(window.confirm(`Vas a cambiar el correo electrónico de tu cuenta.

Al confirmar esta acción:
• Se cerrará tu sesión actual.
• Se reiniciará el código de autenticación en dos pasos (2FA).

¿Confirmas que quieres continuar?`)||a.preventDefault())})});
