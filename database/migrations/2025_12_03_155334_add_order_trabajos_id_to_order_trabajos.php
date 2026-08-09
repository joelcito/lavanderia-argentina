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
        Schema::table('order_trabajos', function (Blueprint $table) {
            $table->unsignedBigInteger('order_trabajos_id')->nullable()->after('usuario_eliminador_id');;
            $table->foreign('order_trabajos_id')->references('id')->on('order_trabajos');

            $table->decimal('posicion_1', 12, 3)->nullable()->after('tipo');
            $table->decimal('posicion_2', 12, 3)->nullable()->after('posicion_1');
            $table->decimal('posicion_3', 12, 3)->nullable()->after('posicion_2');
            $table->decimal('posicion_4', 12, 3)->nullable()->after('posicion_3');
            $table->decimal('nro_prenda_mesa', 12, 3)->nullable()->after('posicion_4');
            $table->string('intensidad', 45)->nullable()->after('nro_prenda_mesa');
            $table->decimal('tiempo', 12, 3)->nullable()->after('intensidad');
            $table->string('disenio', 45)->nullable()->after('tiempo');
            $table->string('talla', 45)->nullable()->after('disenio');
            $table->string('altura', 45)->nullable()->after('talla');
            $table->string('dpi', 45)->nullable()->after('altura');
            $table->decimal('precio_pronosticado', 12,3)->nullable()->after('dpi');
            $table->json('orden_trabajos')->nullable()->after('precio_pronosticado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_trabajos', function (Blueprint $table) {
            $table->dropForeign(['order_trabajos_id']);
            $table->dropColumn('order_trabajos_id');

            $table->dropColumn('posicion_1');
            $table->dropColumn('posicion_2');
            $table->dropColumn('posicion_3');
            $table->dropColumn('posicion_4');
            $table->dropColumn('nro_prenda_mesa');
            $table->dropColumn('intensidad');
            $table->dropColumn('tiempo');
            $table->dropColumn('disenio');
            $table->dropColumn('talla');
            $table->dropColumn('altura');
            $table->dropColumn('dpi');
        });
    }
};
