<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // CONFIGURACIÓN PARA TU BD EN ESPAÑOL
    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'email',
        'password',
        'rol',    // Necesario para asignar 'admin' o 'tecnico' manualmente
        'activo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'activo' => 'boolean',
    ];

    /**
     * EVENTO AUTOMÁTICO (Tu defensa en la sustentación):
     * "Profesor, uso este evento para garantizar que todo usuario con rol 'cliente'
     * tenga su perfil creado en la tabla 'clientes' al instante."
     */
    protected static function booted()
    {
        static::created(function ($user) {
            if ($user->rol === 'cliente') {
                Cliente::create([
                    'id_usuario' => $user->id_usuario,
                    'nombres' => 'Usuario Nuevo', // Valor temporal
                    'estado' => true
                ]);
            }
        });
    }

    // RELACIONES
    public function tecnico()
    {
        return $this->hasOne(Tecnico::class, 'id_usuario', 'id_usuario');
    }

    public function cliente()
    {
        return $this->hasOne(Cliente::class, 'id_usuario', 'id_usuario');
    }
}
