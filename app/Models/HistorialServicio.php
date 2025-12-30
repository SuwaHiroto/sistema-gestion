<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HistorialServicio extends Model
{
    use HasFactory;

    // CORREGIDO: Un solo guion bajo, tal como está en tu migración
    protected $table = 'historial_servicios';
    protected $primaryKey = 'id_historial';

    public $timestamps = false;

    protected $fillable = [
        'id_servicio',
        'id_usuario_responsable',
        'estado_nuevo',
        'comentario',
        'fecha_cambio'
    ];

    protected $casts = [
        'fecha_cambio' => 'datetime',
    ];

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'id_servicio', 'id_servicio');
    }

    public function responsable()
    {
        return $this->belongsTo(User::class, 'id_usuario_responsable', 'id_usuario');
    }
}
