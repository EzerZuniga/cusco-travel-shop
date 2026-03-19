<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Model
{
    use HasFactory, HasApiTokens;

    protected $table = 'usuarios';

    // Campos asignables
    protected $fillable = [
        'nombre',
        'email',
        'password',
        'telefono',
        'is_admin',
        'rol',
    ];

    // Ocultar campos sensibles
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Casts de columnas comunes
    protected $casts = [
        'email_verified_at' => 'datetime',
        'activo' => 'boolean',
        'is_admin' => 'boolean',
    ];

    // Relaciones
    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'usuario_id');
    }

    /**
     * Mutador para hashear la contraseña automáticamente.
     * Si se pasa una contraseña ya hasheada (por ejemplo al importar),
     * evitamos volver a hashearla comprobando su longitud.
     */
    public function setPasswordAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['password'] = $value;
            return;
        }

        // Si ya parece un hash (60 caracteres para bcrypt), no lo re-hasheamos
        if (is_string($value) && strlen($value) === 60 && preg_match('/^\$2y\$|^\$2a\$/', $value)) {
            $this->attributes['password'] = $value;
            return;
        }

        $this->attributes['password'] = Hash::needsRehash($value) ? Hash::make($value) : $value;
    }

    /**
     * Accessor: nombre completo. Si existe columna `apellido`, la concatena.
     */
    public function getNombreCompletoAttribute()
    {
        $nombre = $this->attributes['nombre'] ?? '';
        if (Schema::hasColumn($this->getTable(), 'apellido') && !empty($this->attributes['apellido'])) {
            return trim($nombre . ' ' . $this->attributes['apellido']);
        }
        return $nombre;
    }

    /**
     * Scope para usuarios activos si existe la columna `activo`.
     */
    public function scopeActive($query)
    {
        if (Schema::hasColumn($this->getTable(), 'activo')) {
            return $query->where('activo', true);
        }
        return $query;
    }

    /**
     * Boot del modelo para aplicar valores por defecto si faltan columnas.
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            if (Schema::hasColumn($model->getTable(), 'activo') && !isset($model->activo)) {
                $model->activo = true;
            }
        });
    }
}
