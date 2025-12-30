<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('servicios', function (Blueprint $table) {
            $table->id('id_servicio');

            // Relaciones
            $table->foreignId('id_cliente')->constrained('clientes', 'id_cliente');
            $table->foreignId('id_tecnico')->nullable()->constrained('tecnicos', 'id_tecnico');

            $table->text('descripcion_solicitud');

            // ESTADOS: Cambiado default a 'PENDIENTE' para flujo directo
            $table->string('estado', 30)->default('PENDIENTE')
                ->comment('PENDIENTE, APROBADO, EN_PROCESO, FINALIZADO, CANCELADO');

            // FINANZAS
            // mano_obra: Lo llena el Admin al crear O el Técnico al finalizar
            $table->decimal('mano_obra', 10, 2)->nullable();
            // costo_final_real: Se calcula automáticamente (Mano Obra + Materiales)
            $table->decimal('costo_final_real', 10, 2)->nullable();

            // FECHAS
            $table->dateTime('fecha_solicitud')->useCurrent();
            $table->dateTime('fecha_aprobacion')->nullable();
            $table->dateTime('fecha_inicio')->nullable();
            $table->dateTime('fecha_fin')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('servicios');
    }
};
