<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicioMaterial extends Model
{
    use HasFactory;

    // Agregamos esto para asegurar que apunte a la tabla correcta
    protected $table = 'servicio_materiales';
    protected $primaryKey = 'id_detalle'; // Tu migración tiene esta PK
}
