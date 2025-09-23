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
        Schema::create('procesos', function (Blueprint $table) {
            $table->id();
            $table->foreign('usuario_creador_id')->references('id')->on('users');
            $table->unsignedBigInteger('usuario_creador_id')->nullable();
            $table->foreign('usuario_modificador_id')->references('id')->on('users');
            $table->unsignedBigInteger('usuario_modificador_id')->nullable();
            $table->foreign('usuario_eliminador_id')->references('id')->on('users');
            $table->unsignedBigInteger('usuario_eliminador_id')->nullable();
            //foranea usuario lavador
            $table->foreign('usuario_lavador_id')->references('id')->on('users');
            $table->unsignedBigInteger('usuario_lavador_id')->nullable();
            //foranea order trabajo
            $table->foreign('order_trabajo_id')->references('id')->on('order_trabajos');
            $table->unsignedBigInteger('order_trabajo_id')->nullable();
            //foranea producto
            $table->foreign('producto_id')->references('id')->on('productos');
            $table->unsignedBigInteger('producto_id')->nullable();
            //foranea maquinaria
            $table->foreign('maquinaria_id')->references('id')->on('maquinarias');
            $table->unsignedBigInteger('maquinaria_id')->nullable();
            //foranea tipo proceso
            $table->foreign('tipo_proceso_id')->references('id')->on('tipo_procesos');
            $table->unsignedBigInteger('tipo_proceso_id')->nullable();
            
            $table->dateTime('fecha_ingreso')->nullable();
            $table->dateTime('fecha_salida')->nullable();
            $table->decimal('cantida',12,2)->nullable();
            $table->string('procesoscol')->nullable();
            $table->decimal('porcentaje',12,2)->nullable();
            $table->decimal('gr_litro',12,2)->nullable();
            $table->decimal('tiempo',3)->nullable();
            $table->string('temperatura')->nullable();
            $table->string('ph')->nullable();
            $table->string('rb')->nullable();
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
        Schema::dropIfExists('procesos');
    }
};
