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
        Schema::create('deudas', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('usuario_creador_id')->nullable();
            $table->foreign('usuario_creador_id')->references('id')->on('users');

            $table->unsignedBigInteger('usuario_modificador_id')->nullable();
            $table->foreign('usuario_modificador_id')->references('id')->on('users');

            $table->unsignedBigInteger('usuario_eliminador_id')->nullable();
            $table->foreign('usuario_eliminador_id')->references('id')->on('users');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            $table->string('concepto')->nullable();
            $table->text('descripcion')->nullable();

            $table->decimal('monto_total', 12, 2)->default(0);
            $table->decimal('monto_pagado', 12, 2)->default(0);
            $table->decimal('saldo_pendiente', 12, 2)->default(0);

            $table->string('estado')->default('PENDIENTE');
            // PENDIENTE | PAGADO | ANULADO

            $table->dateTime('fecha')->nullable();
            $table->dateTime('deleted_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deudas');
    }
};
