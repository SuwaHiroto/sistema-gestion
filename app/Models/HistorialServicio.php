<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HistorialServicio extends Model
{
    use HasFactory;

    protected $table = 'historial__servicios';
    protected $primaryKey = 'id_historial';

    // IMPORTANTE: Desactivamos esto porque tu tabla no tiene updated_at
    // y manejamos la fecha manualmente con 'fecha_cambio'
    public $timestamps = false;

    protected $fillable = [
        'id_servicio',
        'id_usuario_responsable',
        'estado_nuevo',
        'comentario',
        'fecha_cambio'
    ];

    protected $casts = [
        'fecha_cambio' => 'datetime'
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
