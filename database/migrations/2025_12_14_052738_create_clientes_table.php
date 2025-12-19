<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id('id_cliente'); // PK propia

            // Relación 1 a 1 con Usuarios
            $table->foreignId('id_usuario')
                ->unique()
                ->constrained('usuarios', 'id_usuario')
                ->onDelete('cascade');

            // Datos del Cliente (Según tu diagrama)
            $table->string('nombres'); // Razón Social o Nombre Completo
            $table->string('direccion')->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('correo')->nullable(); // Correo de contacto (notificaciones)

            $table->boolean('estado')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
