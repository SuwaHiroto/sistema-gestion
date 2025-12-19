<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Servicio extends Model
{
    use HasFactory;

    protected $table = 'servicios';
    protected $primaryKey = 'id_servicio';

    protected $fillable = [
        'id_cliente',
        'id_tecnico',
        'descripcion_solicitud',
        'estado',
        'monto_cotizado',
        'costo_final_real',
        'fecha_solicitud',
        'fecha_aprobacion',
        'fecha_inicio',
        'fecha_fin',
    ];

    protected $casts = [
        'fecha_solicitud' => 'datetime',
        'fecha_aprobacion' => 'datetime',
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
    ];

    // RELACIONES CLAVE
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id_cliente');
    }

    public function tecnico()
    {
        return $this->belongsTo(Tecnico::class, 'id_tecnico', 'id_tecnico');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'id_servicio', 'id_servicio');
    }

    public function historial()
    {
        return $this->hasMany(HistorialServicio::class, 'id_servicio', 'id_servicio');
    }

    public function materiales()
    {
        return $this->belongsToMany(Material::class, 'servicio__materiales', 'id_servicio', 'id_material')
            ->withPivot('cantidad', 'precio_unitario') // <--- ¡Esto es clave!
            ->withTimestamps();
    }
}
