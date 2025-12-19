<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pago extends Model
{
    use HasFactory;
    protected $table = 'pagos';
    protected $primaryKey = 'id_pago';

    protected $fillable = [
        'id_servicio',
        'id_usuario_registra',
        'monto',
        'tipo',
        'validado'
    ];

    public function servicio()
    {
        // Apunta al modelo Servicio (Plural)
        return $this->belongsTo(Servicio::class, 'id_servicio');
    }

    public function registradoPor()
    {
        // Apunta al modelo User (o Usuarios si así lo tienes)
        return $this->belongsTo(User::class, 'id_usuario_registra', 'id_usuario');
    }
}
