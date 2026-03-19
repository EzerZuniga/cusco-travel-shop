<# Script para mostrar últimos logs de Laravel en Windows #>
$log = Join-Path -Path (Get-Location) -ChildPath 'storage\logs\laravel.log'
if (Test-Path $log) {
    Get-Content $log -Tail 200 -Wait
} else {
    Write-Host "No se encontró $log" -ForegroundColor Yellow
}
