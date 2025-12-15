import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'

export default defineConfig({
  plugins: [
    laravel({
      input: ['resources/css/plantilla.css',
        'resources/css/adminStyle.css',
        'resources/css/registroAdmin.css',
        'resources/css/registroUsuario.css',
        'resources/css/resultados.css',
        'resources/css/personas.css',
        'resources/css/layout.css',        
        'resources/css/login.css',
        'resources/css/2fa.css',
        'resources/css/panel.css',
        'resources/css/perfil.css',
        'resources/css/editar.css',
        'resources/css/resultados.css',
        'resources/css/barra.css',
        'resources/js/user-menu.js',
        'resources/js/vistaContra.js',
        'resources/js/mensaje.js',




      ],
      refresh: true,
    }),
  ],
  server: {
    host: true,         // 0.0.0.0 dentro del contenedor
    port: 5173,
    strictPort: true,
    hmr: {
      protocol: 'http',
      host: 'localhost', // <-- el host que usa tu navegador: localhost o 127.0.0.1 (o tu dominio/IP)
      port: 5173,
    },
  },
})