<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tecnico extends Model
{
    use HasFactory;

    protected $table = 'tecnicos';
    protected $primaryKey = 'id_tecnico';

    protected $fillable = [
        'id_usuario',
        'nombres',
        'apellido_paterno',
        'apellido_materno',
        'dni',
        'telefono',
        'especialidad',
        'estado'
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id_usuario');
    }

    public function servicios()
    {
        return $this->hasMany(Servicio::class, 'id_tecnico', 'id_tecnico');
    }

    // Accessor para mostrar nombre completo fácilmente
    public function getNombreCompletoAttribute()
    {
        return "{$this->nombres} {$this->apellido_paterno}";
    }
}
