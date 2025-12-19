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
        Schema::create('servicios', function (Blueprint $table) {
            $table->id('id_servicio');

            // Relaciones (El cliente es obligatorio, el técnico es opcional al inicio)
            $table->foreignId('id_cliente')->constrained('clientes', 'id_cliente');
            $table->foreignId('id_tecnico')->nullable()->constrained('tecnicos', 'id_tecnico');

            $table->text('descripcion_solicitud');

            // ESTADOS (El ciclo de vida)
            $table->string('estado', 30)->default('COTIZANDO')
                ->comment('COTIZANDO, APROBADO, EN_PROCESO, FINALIZADO, CANCELADO');

            // FINANZAS
            $table->decimal('monto_cotizado', 10, 2)->nullable(); // Presupuesto
            $table->decimal('costo_final_real', 10, 2)->nullable(); // Lo que costó al cerrar

            // FECHAS (Auditoría de tiempos)
            $table->dateTime('fecha_solicitud')->useCurrent();
            $table->dateTime('fecha_aprobacion')->nullable(); // ¡NUEVO! Cuando el cliente dice SÍ
            $table->dateTime('fecha_inicio')->nullable();
            $table->dateTime('fecha_fin')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicios');
    }
};
