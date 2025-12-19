<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Material extends Model
{
    use HasFactory;

    protected $table = 'materiales';
    protected $primaryKey = 'id_material';

    protected $fillable = [
        'nombre',
        'unidad',
        'precio_referencial'
    ];

    public function servicios()
    {
        return $this->belongsToMany(Servicio::class, 'servicio_materiales', 'id_material', 'id_servicio');
    }
}
