import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import tailwindcss from '@tailwindcss/vite'

export default defineConfig({
  plugins: [
    laravel({
      input: ['resources/css/app.css', 'resources/js/app.js'],
      // Nada de refresh:true genérico — solo lo necesario
      refresh: [
        'resources/views/**/*.blade.php',
        'app/Livewire/**/*.php',     // componentes Livewire (PHP)
        'routes/**/*.php',
      ],
    }),
    tailwindcss(),
  ],
  server: {
    hmr: { host: 'localhost' },
    watch: {
      usePolling: false, // activa polling solo si estás en VM/FS raro
      ignored: [
        '**/node_modules/**',
        '**/vendor/**',
        '**/storage/**',
        '**/public/build/**', // ignora el build, pero NO todo public
        '**/.git/**',
        '**/logs/**',
      ],
      // Si tu FS tarda en escribir, descomenta:
      // awaitWriteFinish: { stabilityThreshold: 150, pollInterval: 50 },
    },
  },
  optimizeDeps: {
    // Si usas Alpine o libs comunes en el front, prebundlea aquí:
    // include: ['alpinejs', 'axios'],
  },
  build: { sourcemap: false },
})
