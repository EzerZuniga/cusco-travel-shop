<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

/**
 * Modelo Tour
 *
 * Representa un tour ofrecido en la plataforma. Se añaden scopes y helpers
 * para búsquedas, filtrado por precio/estado, y utilidades como cálculo de
 * plazas disponibles si la migración define capacidad.
 */
class Tour extends Model
{
    use HasFactory;

    protected $table = 'tours';

    protected $fillable = [
        'titulo',
        'slug',
        'descripcion',
        'precio',
        'duracion',
        'imagen',
        'activo',
        // 'destacado' or 'capacidad' pueden existir según migraciones
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'activo' => 'boolean',
    ];

    /**
     * Inicializar valores por defecto (si fuera necesario).
     */
    protected static function booted()
    {
        static::creating(function (Tour $tour) {
            if (!isset($tour->activo)) {
                $tour->activo = true;
            }
        });
    }

    /**
     * Relación: un tour tiene muchas reservas.
     */
    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'tour_id');
    }

    /**
     * Scope: solo tours activos.
     */
    public function scopeActive($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope: tours marcados como destacados (si la columna existe).
     */
    public function scopeFeatured($query)
    {
        if (Schema::hasColumn('tours', 'destacado')) {
            return $query->where('destacado', true)->where('activo', true);
        }
        // Si no hay columna 'destacado', devolvemos los activos ordenados por fecha
        return $query->where('activo', true)->orderBy('updated_at', 'desc');
    }

    /**
     * Scope: búsqueda simple por título o descripción.
     */
    public function scopeSearch($query, ?string $term)
    {
        if (empty($term)) return $query;
        $term = trim($term);
        return $query->where(function ($q) use ($term) {
            $q->where('titulo', 'like', "%{$term}%")
              ->orWhere('descripcion', 'like', "%{$term}%");
        });
    }

    /**
     * Scope: filtrar por rango de precio.
     */
    public function scopePriceBetween($query, $min = null, $max = null)
    {
        if (!is_null($min)) $query->where('precio', '>=', (float) $min);
        if (!is_null($max)) $query->where('precio', '<=', (float) $max);
        return $query;
    }

    /**
     * Accesor: precio formateado con 2 decimales.
     */
    public function getPriceFormattedAttribute(): string
    {
        return number_format($this->precio ?? 0, 2);
    }

    /**
     * Calcula las plazas disponibles para una fecha dada.
     * Requiere que exista la columna `capacidad` y que las reservas tengan `fecha` y `personas`.
     * Devuelve null si no se puede calcular por falta de columna.
     */
    public function availableSeats(?\DateTimeInterface $date = null): ?int
    {
        try {
            if (!Schema::hasColumn('tours', 'capacidad')) return null;

            $capacidad = (int) $this->capacidad;
            $date = $date ? Carbon::parse($date) : Carbon::today();

            $reserved = $this->reservas()->whereDate('fecha', $date->toDateString())->sum('personas');
            return max(0, $capacidad - (int) $reserved);
        } catch (\Throwable $e) {
            Log::warning('Error calculando availableSeats: ' . $e->getMessage(), ['tour_id' => $this->id]);
            return null;
        }
    }

    /**
     * Determina si el tour está disponible en una fecha (true/false).
     * Si no hay columna `capacidad`, asumimos disponible si `activo` es true.
     */
    public function isAvailable(?\DateTimeInterface $date = null): bool
    {
        if (!$this->activo) return false;
        $seats = $this->availableSeats($date);
        if (is_null($seats)) return true; // No hay dato de capacidad -> asumimos disponible
        return $seats > 0;
    }
}
