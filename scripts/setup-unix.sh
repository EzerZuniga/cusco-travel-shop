#!/usr/bin/env bash
# Script de preparación para Linux/Mac
set -euo pipefail
ROOT_DIR="$(cd "$(dirname "$0")/.." && pwd)"
cd "$ROOT_DIR"

echo "== Preparando proyecto Cusco Travel Shop (Unix) =="

if [ ! -f database/database.sqlite ]; then
  echo "Creando database/database.sqlite..."
  mkdir -p database
  touch database/database.sqlite
else
  echo "Archivo database/database.sqlite ya existe."
fi

echo "Comprobando PHP..."
php -v || { echo "PHP no encontrado"; exit 1; }

if ! command -v composer >/dev/null 2>&1; then
  echo "Composer no encontrado. Instálalo e intenta de nuevo."; exit 1
fi

echo "Instalando dependencias..."
composer install --no-interaction

echo "Generando APP_KEY..."
php artisan key:generate --force

echo "Ejecutando migraciones..."
php artisan migrate --force

echo "Sembrando datos (si existen seeders)..."
php artisan db:seed --force || true

echo "Limpiando caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear || true

echo "Optimizing..."
php artisan optimize || true

echo "Listo. Para arrancar el servidor: cd public && php -S 127.0.0.1:8080 index.php"
