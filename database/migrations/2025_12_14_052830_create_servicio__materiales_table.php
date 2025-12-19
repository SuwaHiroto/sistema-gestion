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
        Schema::create('servicio__materiales', function (Blueprint $table) {
            $table->id('id_detalle'); // PK propia opcional pero útil

            $table->foreignId('id_servicio')->constrained('servicios', 'id_servicio')->onDelete('cascade');
            $table->foreignId('id_material')->constrained('materiales', 'id_material');

            $table->decimal('cantidad', 8, 2);

            // CORRECCIÓN: Nombre claro para saber que es unitario
            $table->decimal('precio_unitario', 10, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicio__materiales');
    }
};
