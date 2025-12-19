<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Si existe la tabla mal escrita, la renombramos
        if (Schema::hasTable('hitorial__servicios')) {
            Schema::rename('hitorial__servicios', 'historial_servicios');
        }
    }

    public function down()
    {
        if (Schema::hasTable('historial_servicios')) {
            Schema::rename('historial_servicios', 'hitorial__servicios');
        }
    }
};
