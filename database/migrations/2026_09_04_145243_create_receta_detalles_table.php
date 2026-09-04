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
        Schema::create('receta_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreign('usuario_creador_id')->references('id')->on('users');
            $table->unsignedBigInteger('usuario_creador_id')->nullable();
            $table->foreign('usuario_modificador_id')->references('id')->on('users');
            $table->unsignedBigInteger('usuario_modificador_id')->nullable();
            $table->foreign('usuario_eliminador_id')->references('id')->on('users');
            $table->unsignedBigInteger('usuario_eliminador_id')->nullable();

            $table->foreign('receta_id')->references('id')->on('recetas');
            $table->unsignedBigInteger('receta_id')->nullable();
            $table->foreign('tipo_proceso_id')->references('id')->on('tipo_procesos');
            $table->unsignedBigInteger('tipo_proceso_id')->nullable();
            $table->foreign('producto_id')->references('id')->on('productos');
            $table->unsignedBigInteger('producto_id')->nullable();

            $table->integer('orden_proceso')->nullable();
            $table->integer('orden_producto')->nullable();
            $table->decimal('porcentaje', 12, 5)->nullable();
            $table->decimal('cantidad', 12, 5)->nullable();
            $table->decimal('total', 12, 5)->nullable();
            $table->decimal('tiempo', 12, 5)->nullable();
            $table->decimal('temperatura', 12, 5)->nullable();
            $table->decimal('ph', 12, 5)->nullable();
            $table->decimal('rb', 12, 5)->nullable();
            $table->text('descripcion')->nullable();

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
        Schema::dropIfExists('receta_detalles');
    }
};
