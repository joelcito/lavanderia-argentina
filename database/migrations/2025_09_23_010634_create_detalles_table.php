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
        Schema::create('detalles', function (Blueprint $table) {
            $table->id();
            $table->foreign('usuario_creador_id')->references('id')->on('users');
            $table->unsignedBigInteger('usuario_creador_id')->nullable();
            $table->foreign('usuario_modificador_id')->references('id')->on('users');
            $table->unsignedBigInteger('usuario_modificador_id')->nullable();
            $table->foreign('usuario_eliminador_id')->references('id')->on('users');
            $table->unsignedBigInteger('usuario_eliminador_id')->nullable();
            //foranea order trabajo
            $table->foreign('order_trabajo_id')->references('id')->on('order_trabajos');
            $table->unsignedBigInteger('order_trabajo_id')->nullable();
            //foranea tipo tela
            $table->foreign('tipo_tela_id')->references('id')->on('tipo_telas');
            $table->unsignedBigInteger('tipo_tela_id')->nullable();
            //foranea color tela
            $table->foreign('color_tela_id')->references('id')->on('color_telas');
            $table->unsignedBigInteger('color_tela_id')->nullable();
            //foranea caracteristica
            $table->foreign('caracteristica_id')->references('id')->on('caracteristicas');
            $table->unsignedBigInteger('caracteristica_id')->nullable();
            //foranea nombre tela
            $table->foreign('nombre_tela_id')->references('id')->on('nombre_telas');
            $table->unsignedBigInteger('nombre_tela_id')->nullable();
            //foranea prelavado
            $table->foreign('prelavado_id')->references('id')->on('prelavados');
            $table->unsignedBigInteger('prelavado_id')->nullable();
            //foranea focalizado
            $table->foreign('focalizado_id')->references('id')->on('focalizados');
            $table->unsignedBigInteger('focalizado_id')->nullable();
            //foranea prenda
            $table->foreign('prenda_id')->references('id')->on('prendas');
            $table->unsignedBigInteger('prenda_id')->nullable();

            $table->text('descripcion_adicional')->nullable();
            $table->decimal('precio',12,2)->nullable();
            $table->decimal('cantidad',12,2)->nullable();
            $table->decimal('descuento',12,2)->nullable();
            $table->decimal('importe',12,2)->nullable();
            $table->decimal('peso',12,2)->nullable();
            $table->string('numero_ojales')->nullable();

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
        Schema::dropIfExists('detalles');
    }
};
