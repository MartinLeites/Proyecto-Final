// resources/js/user-menu.js

document.addEventListener('DOMContentLoaded', () => {
    const menu     = document.getElementById('userMenu');
    const trigger  = document.getElementById('userTrigger');
    const dropdown = document.getElementById('userDropdown');

    // Si no hay menú (usuario no logueado), no hacemos nada
    if (!menu || !trigger || !dropdown) {
        return;
    }

    const cerrarMenu = () => {
        menu.classList.remove('is-open');
        trigger.setAttribute('aria-expanded', 'false');
    };

    const toggleMenu = (event) => {
        event.stopPropagation();

        const estabaAbierto = menu.classList.contains('is-open');
        if (estabaAbierto) {
            cerrarMenu();
        } else {
            menu.classList.add('is-open');
            trigger.setAttribute('aria-expanded', 'true');
        }
    };

    // Abrir/cerrar al hacer click en el botón
    trigger.addEventListener('click', toggleMenu);

    // Cerrar al hacer click fuera
    document.addEventListener('click', (event) => {
        if (!menu.contains(event.target)) {
            cerrarMenu();
        }
    });

    // Cerrar con Escape
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            cerrarMenu();
        }
    });
});
