<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('maquinarias', function (Blueprint $table) {
            $table->enum('estado_maquina', ['DISPONIBLE', 'EN PROCESO', 'NO DISPONIBLE'])
                ->default('DISPONIBLE')
                ->after('tipo');
        });
    }

    public function down()
    {
        Schema::table('maquinarias', function (Blueprint $table) {
            $table->dropColumn('estado');
        });
    }
};
