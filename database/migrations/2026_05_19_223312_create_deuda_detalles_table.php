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
        Schema::create('deuda_detalles', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('usuario_creador_id')->nullable();
            $table->foreign('usuario_creador_id')->references('id')->on('users');

            $table->unsignedBigInteger('usuario_modificador_id')->nullable();
            $table->foreign('usuario_modificador_id')->references('id')->on('users');

            $table->unsignedBigInteger('usuario_eliminador_id')->nullable();
            $table->foreign('usuario_eliminador_id')->references('id')->on('users');

            $table->unsignedBigInteger('deuda_id');
            $table->foreign('deuda_id')->references('id')->on('deudas');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            $table->unsignedBigInteger('pago_id')->nullable();
            $table->foreign('pago_id')->references('id')->on('pagos');

            $table->string('tipo_movimiento')->nullable();
            // INGRESO = deuda creada
            // SALIDA = descuento aplicado

            $table->decimal('monto', 12, 2)->default(0);
            $table->text('descripcion')->nullable();
            $table->dateTime('fecha')->nullable();
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
        Schema::dropIfExists('deuda_detalles');
    }
};
