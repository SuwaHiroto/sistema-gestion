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
        Schema::create('pagos', function (Blueprint $table) {
            $table->id('id_pago'); // Tu PK personalizada

            // Relación con el Servicio
            $table->unsignedBigInteger('id_servicio');
            $table->foreign('id_servicio')->references('id_servicio')->on('servicios')->onDelete('cascade');

            // Relación con el Usuario que cobra (El campo nuevo)
            $table->unsignedBigInteger('id_usuario_registra');
            $table->foreign('id_usuario_registra')->references('id_usuario')->on('usuarios');

            // Datos del pago
            $table->decimal('monto', 10, 2);
            $table->string('tipo'); // Este es tu "METODO de pago"
            $table->boolean('validado')->default(false); // Tu campo de validación

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
