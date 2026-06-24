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
        Schema::create('cotizaciones', function (Blueprint $table) {
            $table->id();
            $table->foreign('usuario_creador_id')->references('id')->on('users');
            $table->unsignedBigInteger('usuario_creador_id')->nullable();
            $table->foreign('usuario_modificador_id')->references('id')->on('users');
            $table->unsignedBigInteger('usuario_modificador_id')->nullable();
            $table->foreign('usuario_eliminador_id')->references('id')->on('users');
            $table->unsignedBigInteger('usuario_eliminador_id')->nullable();

            $table->foreign('cliente_id')->references('id')->on('users');
            $table->unsignedBigInteger('cliente_id')->nullable();

            $table->decimal('mano_obra',12,2)->nullable();
            $table->decimal('servicio_basico',12,2)->nullable();
            $table->decimal('mantenimiento',12,2)->nullable();
            $table->decimal('interes_bancario',12,2)->nullable();

            $table->decimal('costo',12,2)->nullable();
            $table->decimal('precio',12,2)->nullable();
            $table->decimal('utilidad',12,2)->nullable();

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
        Schema::dropIfExists('cotizaciones');
    }
};
