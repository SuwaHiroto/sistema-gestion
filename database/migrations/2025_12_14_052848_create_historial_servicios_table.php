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
        Schema::create('historial_servicios', function (Blueprint $table) {
            $table->id('id_historial');

            $table->foreignId('id_servicio')->constrained('servicios', 'id_servicio')->onDelete('cascade');

            // AUDITORÍA: ¿Quién cambió el estado?
            $table->foreignId('id_usuario_responsable')->constrained('usuarios', 'id_usuario');

            $table->string('estado_nuevo', 30);
            $table->text('comentario')->nullable();

            $table->timestamp('fecha_cambio')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historial_servicios');
    }
};
