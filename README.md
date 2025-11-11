<!-- README profesional para Cusco Travel Shop -->

<h1 align="center">Cusco Travel Shop</h1>
<p align="center">
  <img src="https://cdn.trustindex.io/companies/0a/0a0bc9198231g1e4/avatar.jpg" alt="Perú"/>
</p>
<p align="center"><strong>Tienda de turismo en Cusco</strong><br>Sistema completo de reservas y tours</p>

<hr>

<h2>Estructura del Proyecto</h2>
<div>
  <h3>Frontend <span style="font-size:1em;">🌐</span></h3>
  <ul>
    <li><b>pages/</b>: Páginas principales (index, tours, about, blog, gallery, carrito, contact)</li>
    <li><b>components/</b>: Componentes reutilizables (header, footer, navbar, modals)</li>
    <li><b>assets/</b>: Recursos estáticos (CSS, JS, imágenes, fuentes, iconos)</li>
    <li><b>data/</b>: Datos de prueba en formato JSON</li>
    <li><b>utils/</b>: Funciones utilitarias y helpers</li>
    <li><b>vendor/</b>: Librerías externas (AOS, Swiper)</li>
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
  <h3>Frontend</h3>
  <ul>
    <li>HTML5, CSS3, JavaScript (ES6+)</li>
    <li>Bootstrap 5</li>
    <li>AOS (Animate On Scroll)</li>
    <li>Swiper (Carruseles)</li>
  </ul>
  <h3>Backend</h3>
  <ul>
    <li>Laravel 10</li>
    <li>PHP 8.1+</li>
    <li>MySQL</li>
    <li>RESTful API</li>
  </ul>
</div>

<hr>

<h2>📝 Instalación y Configuración</h2>
<div>
  <h3>Frontend</h3>
  <pre>
    <code>
      cd frontend
      npm install
      npm run dev
    </code>
  </pre>
  <h3>Backend</h3>
  <pre>
    <code>
      cd backend
      composer install
      cp .env.example .env
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

## Migración de estructura (automatizada)

Se realizó una reorganización del proyecto para seguir una estructura tipo Laravel:

- Copia de seguridad: `cusco-travel-shop-backup/` creada en la misma carpeta raíz.
- Carpetas creadas/actualizadas: `app/`, `config/`, `database/`, `public/assets/`, `resources/views/`, `routes/`, `storage/`.
- Se movieron controladores, modelos, vistas y assets reutilizando el código existente. No se eliminaron los originales; revisa `backend/` y `frontend/` si necesitas recuperar algo.

Si algo quedó mal o quieres revertir, puedes restaurar desde la copia de seguridad.
