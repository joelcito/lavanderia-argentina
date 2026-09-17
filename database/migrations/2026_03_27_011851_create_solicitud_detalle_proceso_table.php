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
        Schema::create('solicitud_detalle_proceso', function (Blueprint $table) {
            $table->id();

            // Relación principal (ya incluye foreign key)
            $table->foreignId('solicitud_id')->constrained('solicitudes')->cascadeOnDelete();

            // Datos clave
            $table->unsignedBigInteger('order_trabajo_id');
            $table->unsignedBigInteger('factura_id')->nullable();

            // Tipo de proceso
            $table->enum('tipo_proceso', ['FOCALIZADO', 'PLANCHADO'])->index();

            // Categoría (solo para planchado)
            $table->string('categoria')->nullable();

            // Cantidad
            $table->integer('cantidad')->default(0);

            // Auditoría
            $table->unsignedBigInteger('usuario_creador_id')->nullable();
            $table->unsignedBigInteger('usuario_modificador_id')->nullable();
            $table->unsignedBigInteger('usuario_eliminador_id')->nullable();

            // Soft delete + fechas
            $table->softDeletes();
            $table->timestamps();

            // Foreign keys
            $table->foreign('order_trabajo_id')->references('id')->on('order_trabajos');
            $table->foreign('factura_id')->references('id')->on('facturas');

            $table->foreign('usuario_creador_id')->references('id')->on('users');
            $table->foreign('usuario_modificador_id')->references('id')->on('users');
            $table->foreign('usuario_eliminador_id')->references('id')->on('users');

            // Índices
            $table->index(['solicitud_id', 'tipo_proceso']);
            $table->index(['order_trabajo_id', 'tipo_proceso']);
            $table->index('created_at'); // ✅ correcto
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitud_detalle_proceso');
    }
};
