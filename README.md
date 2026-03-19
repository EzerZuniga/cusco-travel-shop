<!-- README profesional para Cusco Travel Shop -->

<h1 align="center">Cusco Travel Shop</h1>
<p align="center">
  <img src="https://cdn.trustindex.io/companies/0a/0a0bc9198231g1e4/avatar.jpg" alt="Perú"/>
</p>
<p align="center"><strong>Tienda de turismo en Cusco</strong><br>Sistema completo de reservas y tours</p>

<hr>

<h2>Estructura del Proyecto</h2>
<div>
  <h3>Vistas (Blade) <span style="font-size:1em;">🌐</span></h3>
    <ul>
      <li><b>resources/views/pages/</b>: Páginas principales (index, tours, blog, contacto)</li>
      <li><b>resources/views/components/</b>: Componentes reutilizables (header, footer, navbar, modals)</li>
      <li><b>public/assets/</b>: Recursos estáticos (CSS, JS, imágenes, fuentes, iconos)</li>
    </ul>

  <h3>Backend <span style="font-size:1em;">🛠️</span></h3>
  <ul>
    <li><b>app/</b>: Lógica de la aplicación (Controllers, Models, Providers)</li>
    <li><b>database/</b>: Migraciones, seeders y factories</li>
    <li><b>resources/</b>: Vistas Blade y assets</li>
    <li><b>routes/</b>: Definición de rutas web y API</li>
    <li><b>public/</b>: Punto de entrada y archivos públicos</li>
  </ul>

  <h3>Base de Datos <span style="font-size:1em;">🗄️</span></h3>
  <ul>
    <li><b>turismo.sql</b>: Script principal de estructura</li>
    <li><b>backup_2025_10_27.sql</b>: Copia de seguridad</li>
    <li><b>data_test.sql</b>: Datos de ejemplo para testing</li>
  </ul>
</div>

<hr>

<h2>🚀 Tecnologías Utilizadas</h2>
<div>
  <h3>Stack</h3>
  <ul>
    <li>Laravel 10 (Blade templates)</li>
    <li>PHP 8.2+</li>
    <li>MySQL</li>
    <li>Bootstrap 5 + JS en <code>public/assets</code></li>
  </ul>
</div>

<hr>

<h2>📝 Instalación y Configuración</h2>
<div>
  <h3>Aplicación Laravel</h3>
  <pre>
    <code>
      cd cusco-travel-shop
      composer install
      php artisan key:generate
      php artisan migrate --seed
      php artisan serve
    </code>
  </pre>
  <h3>Base de Datos</h3>
  <pre>
    <code>
      mysql -u root -p < database_mysql/turismo.sql
      mysql -u root -p turismo < database_mysql/data_test.sql
    </code>
  </pre>
</div>

<hr>

<h2>✨ Características</h2>
<ul>
  <li>🏛️ <b>Tours:</b> Catálogo completo de tours en Cusco</li>
  <li>🛒 <b>Carrito:</b> Sistema de reservas y carrito de compras</li>
  <li>👤 <b>Usuarios:</b> Registro y autenticación de usuarios</li>
  <li>📱 <b>Responsive:</b> Diseño adaptable a todos los dispositivos</li>
  <li>🎨 <b>Galería:</b> Galería de imágenes de destinos</li>
  <li>📝 <b>Blog:</b> Sistema de noticias y artículos</li>
  <li>💳 <b>Pagos:</b> Integración con múltiples métodos de pago</li>
  <li>🔧 <b>Admin:</b> Panel administrativo completo</li>
</ul>

<hr>

<h2>📄 Licencia</h2>
<p>MIT License</p>
<p>Plataforma web de turismo desarrollada para la promoción y gestión de tours en Cusco. El sistema integra un sitio web público moderno (HTML5, Bootstrap y JavaScript) con un backend robusto en Laravel y base de datos MySQL. Permite la visualización de tours, reservas, gestión de usuarios, carrito de compras, blog turístico y panel administrativo.</p>

<hr>

<p align="center">
  <b>Desarrollado por EzerZuniga &copy; 2025</b>
</p>

## Notas de mantenimiento

- El proyecto se ha simplificado para usar únicamente Laravel + Blade.
- Se eliminó la toolchain de Node (package.json, node_modules) y migraciones duplicadas vacías.
- Los assets se sirven desde `public/assets`. Si deseas usar Vite/Mix en el futuro, podemos configurarlo y mover los assets a `resources/`.
