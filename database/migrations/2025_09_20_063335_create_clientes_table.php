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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->foreign('usuario_creador_id')->references('id')->on('users');
            $table->unsignedBigInteger('usuario_creador_id')->nullable();
            $table->foreign('usuario_modificador_id')->references('id')->on('users');
            $table->unsignedBigInteger('usuario_modificador_id')->nullable();
            $table->foreign('usuario_eliminador_id')->references('id')->on('users');
            $table->unsignedBigInteger('usuario_eliminador_id')->nullable();

            $table->string('nombres')->nullable();
            $table->string('ap_paterno')->nullable();
            $table->string('ap_materno')->nullable();
            $table->string('celular')->nullable();
            $table->string('cedula')->nullable();
            $table->string('nit')->nullable();
            $table->string('razon_social')->nullable();
            $table->text('direccion')->nullable();
            $table->text('imagen')->nullable();
            $table->text('imagen_CI_anverso')->nullable();
            $table->text('imagen_CI_reverso')->nullable();
            $table->text('nombre_referencia_1')->nullable();
            $table->text('celular_referencia_1')->nullable();
            $table->text('nombre_referencia_2')->nullable();
            $table->text('celular_referencia_2')->nullable();
            $table->text('nombre_referencia_3')->nullable();
            $table->text('celular_referencia_3')->nullable();

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
        Schema::dropIfExists('clientes');
    }
};
