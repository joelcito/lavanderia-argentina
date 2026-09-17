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
        Schema::create('factura_recetas', function (Blueprint $table) {
            $table->id();
            $table->foreign('usuario_creador_id')->references('id')->on('users');
            $table->unsignedBigInteger('usuario_creador_id')->nullable();
            $table->foreign('usuario_modificador_id')->references('id')->on('users');
            $table->unsignedBigInteger('usuario_modificador_id')->nullable();
            $table->foreign('usuario_eliminador_id')->references('id')->on('users');
            $table->unsignedBigInteger('usuario_eliminador_id')->nullable();

            $table->foreign('factura_id')->references('id')->on('facturas');
            $table->unsignedBigInteger('factura_id')->nullable();
            $table->foreign('receta_id')->references('id')->on('recetas');
            $table->unsignedBigInteger('receta_id')->nullable();

            $table->unsignedBigInteger('tipo_tela_id')->nullable();
            $table->foreign('tipo_tela_id')->references('id')->on('tipo_telas');

            $table->unsignedBigInteger('color_tela_id')->nullable();
            $table->foreign('color_tela_id')->references('id')->on('color_telas');

            $table->unsignedBigInteger('nombre_tela_id')->nullable();
            $table->foreign('nombre_tela_id')->references('id')->on('nombre_telas');

            $table->unsignedBigInteger('tipo_proceso_id')->nullable();
            $table->foreign('tipo_proceso_id')->references('id')->on('tipo_procesos');

            $table->unsignedBigInteger('prelavado_id')->nullable();
            $table->foreign('prelavado_id')->references('id')->on('prelavados');

            $table->unsignedBigInteger('focalizado_id')->nullable();
            $table->foreign('focalizado_id')->references('id')->on('focalizados');

            $table->unsignedBigInteger('caracteristica_id')->nullable();
            $table->foreign('caracteristica_id')->references('id')->on('caracteristicas');

            $table->unsignedBigInteger('nevado_id')->nullable();
            $table->foreign('nevado_id')->references('id')->on('nevados');

            $table->unsignedBigInteger('order_trabajo_id')->nullable();
            $table->foreign('order_trabajo_id')->references('id')->on('order_trabajos');

            $table->string('nombre')->nullable();

            $table->text('descripcion')->nullable();
            $table->decimal('peso_kg',12,5)->nullable();
            $table->decimal('peso_gr',12,5)->nullable();

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
        Schema::dropIfExists('factura_recetas');
    }
};
