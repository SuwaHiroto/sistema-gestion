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
        Schema::create('tecnicos', function (Blueprint $table) {
            $table->id('id_tecnico'); // PK propia

            // Relación 1 a 1 con Usuarios
            $table->foreignId('id_usuario')
                ->unique() // ¡Importante! Un usuario solo puede ser un técnico
                ->constrained('usuarios', 'id_usuario')
                ->onDelete('cascade');

            // Datos Personales (Según tu diagrama)
            $table->string('nombres');
            $table->string('apellido_paterno');
            $table->string('apellido_materno');
            $table->string('dni', 8)->unique(); // DNI único
            $table->string('telefono', 20)->nullable();
            $table->string('especialidad');

            $table->boolean('estado')->default(true); // Activo/Inactivo
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tecnicos');
    }
};
