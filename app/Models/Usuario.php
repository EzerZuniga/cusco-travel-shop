<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;

    protected $table = 'usuarios';

    // Campos asignables
    protected $fillable = [
        'nombre',
        'email',
        'password',
        'telefono',
    ];

    // Ocultar campos sensibles
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relaciones
    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'usuario_id');
    }
}
