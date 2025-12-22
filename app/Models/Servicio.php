<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    use HasFactory;

    protected $table = 'servicios';
    protected $primaryKey = 'id_servicio';

    protected $fillable = [
        'id_cliente',
        'id_tecnico', // <--- Asegúrate de que este campo esté aquí
        'descripcion_solicitud',
        'estado',
        'mano_obra',
        'monto_cotizado',
        'fecha_solicitud',
        'fecha_inicio',
        'fecha_fin'
    ];

    protected $casts = [
        'fecha_solicitud' => 'datetime',
        'created_at' => 'datetime',
    ];

    // Relación: Un servicio pertenece a UN cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id_cliente');
    }

    // Relación: Un servicio pertenece a UN técnico
    public function tecnico()
    {
        // Usamos belongsTo porque la FK 'id_tecnico' está en esta tabla 'servicios'
        return $this->belongsTo(Tecnico::class, 'id_tecnico', 'id_tecnico');
    }

    // ... Tus otras relaciones (historial, pagos, materiales) siguen igual ...
    public function historial()
    {
        return $this->hasMany(HistorialServicio::class, 'id_servicio', 'id_servicio');
    }
    public function pagos()
    {
        return $this->hasMany(Pago::class, 'id_servicio', 'id_servicio');
    }
    public function materiales()
    {
        return $this->belongsToMany(Material::class, 'servicio__materiales', 'id_servicio', 'id_material')
            ->withPivot('cantidad', 'precio_unitario');
    }
}
