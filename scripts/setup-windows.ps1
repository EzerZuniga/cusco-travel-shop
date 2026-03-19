<#
  Script de preparación para Windows (PowerShell)
  - Crea el archivo SQLite si no existe
  - Instala dependencias (composer) si falta
  - Genera APP_KEY
  - Ejecuta migraciones y seeders
  - Limpia caches

  Ejecutar desde la raíz del proyecto:
    .\scripts\setup-windows.ps1
#>

Write-Host "== Preparando proyecto Cusco Travel Shop (Windows) ==" -ForegroundColor Cyan

$root = Split-Path -Parent $MyInvocation.MyCommand.Definition
Set-Location $root

if (-not (Test-Path "database\database.sqlite")) {
    Write-Host "Creando archivo database/database.sqlite..." -ForegroundColor Yellow
    New-Item -ItemType File -Path "database\database.sqlite" -Force | Out-Null
} else {
    Write-Host "Archivo database/database.sqlite ya existe." -ForegroundColor Green
}

Write-Host "Comprobando PHP..." -ForegroundColor Cyan
php -v

Write-Host "Comprobando Composer..." -ForegroundColor Cyan
try {
    composer --version
} catch {
    Write-Host "Composer no encontrado. Por favor instala Composer antes de continuar." -ForegroundColor Red
    exit 1
}

Write-Host "Instalando dependencias (composer install)..." -ForegroundColor Cyan
composer install --no-interaction

Write-Host "Generando APP_KEY..." -ForegroundColor Cyan
php artisan key:generate --force

Write-Host "Ejecutando migraciones..." -ForegroundColor Cyan
php artisan migrate --force

Write-Host "Sembrando datos... (si existen seeders)" -ForegroundColor Cyan
php artisan db:seed --force

Write-Host "Limpiando caches..." -ForegroundColor Cyan
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

Write-Host "Optimizing..." -ForegroundColor Cyan
php artisan optimize

Write-Host "Listo. Para arrancar el servidor en localhost:8080 ejecuta:" -ForegroundColor Green
Write-Host "  cd public; php -S 127.0.0.1:8080 index.php" -ForegroundColor Yellow
