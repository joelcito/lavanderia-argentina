<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lasers', function (Blueprint $table) {
            $table->id();
            $table->foreign('usuario_creador_id')->references('id')->on('users');
            $table->unsignedBigInteger('usuario_creador_id')->nullable();
            $table->foreign('usuario_modificador_id')->references('id')->on('users');
            $table->unsignedBigInteger('usuario_modificador_id')->nullable();
            $table->foreign('usuario_eliminador_id')->references('id')->on('users');
            $table->unsignedBigInteger('usuario_eliminador_id')->nullable();

            $table->foreign('order_trabajos_id')->references('id')->on('order_trabajos');
            $table->unsignedBigInteger('order_trabajos_id')->nullable();
            $table->decimal('posicion_1', 12, 2);
            $table->decimal('posicion_2', 12, 2);
            $table->decimal('posicion_3', 12, 2);
            $table->decimal('posicion_4', 12, 2);
            $table->decimal('nro_prenda_mesa', 12, 2);
            $table->string('intensidad', 45);
            $table->decimal('tiempo', 12, 2);
            $table->string('disenio', 45);
            $table->string('talla', 45);
            $table->string('cantidad', 45);
            $table->string('altura', 45);
            $table->string('dpi', 45);
            $table->string('estado')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lasers');
    }
};
