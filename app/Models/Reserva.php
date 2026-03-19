<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Reserva extends Model
{
    use HasFactory;

    protected $table = 'reservas';

    protected $fillable = [
        'usuario_id',
        'tour_id',
        'fecha',
        'personas',
        'total',
        'estado',
    ];

    protected $casts = [
        // 'fecha' como instancia de fecha (Carbon)
        'fecha' => 'date',
        'total' => 'decimal:2',
    ];

    // Estados posibles para una reserva
    public const STATUS_PENDING = 'pendiente';
    public const STATUS_CONFIRMED = 'confirmada';
    public const STATUS_CANCELLED = 'cancelada';

    /**
     * Inicializar valores por defecto en la creación.
     */
    protected static function booted()
    {
        static::creating(function (Reserva $reserva) {
            if (empty($reserva->estado)) {
                $reserva->estado = self::STATUS_PENDING;
            }
        });
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function tour()
    {
        return $this->belongsTo(Tour::class, 'tour_id');
    }

    /**
     * Scope para filtrar reservas pendientes.
     */
    public function scopePending($query)
    {
        return $query->where('estado', self::STATUS_PENDING);
    }

    /**
     * Scope para filtrar reservas confirmadas.
     */
    public function scopeConfirmed($query)
    {
        return $query->where('estado', self::STATUS_CONFIRMED);
    }

    /**
     * Accesor para obtener el total formateado como string (ej. 123.45)
     */
    public function getTotalFormattedAttribute(): string
    {
        return number_format($this->total ?? 0, 2);
    }

    /**
     * Determina si la reserva puede ser cancelada por el usuario.
     * Lógica por defecto: solo si está en estado pendiente y la fecha no ha pasado.
     */
    public function isCancelable(): bool
    {
        try {
            if ($this->estado !== self::STATUS_PENDING) return false;
            if (!$this->fecha) return false;

            // Carbon instance
            return $this->fecha->isFuture();
        } catch (\Throwable $e) {
            Log::warning('isCancelable error: ' . $e->getMessage(), ['id' => $this->id]);
            return false;
        }
    }

    /**
     * Scope para obtener reservas de un usuario.
     */
    public function scopeByUser($query, int $usuarioId)
    {
        return $query->where('usuario_id', $usuarioId);
    }
}
