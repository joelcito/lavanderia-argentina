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
            $table->foreign('prelavado_id')->references('id')->on('prelavados');
            $table->unsignedBigInteger('prelavado_id')->nullable();
            $table->foreign('nevado_id')->references('id')->on('nevados');
            $table->unsignedBigInteger('nevado_id')->nullable();
            $table->foreign('focalizado_id')->references('id')->on('focalizados');
            $table->unsignedBigInteger('focalizado_id')->nullable();

            $table->foreign('tipo_tela_id')->references('id')->on('tipo_telas');
            $table->unsignedBigInteger('tipo_tela_id')->nullable();
            $table->foreign('color_tela_id')->references('id')->on('color_telas');
            $table->unsignedBigInteger('color_tela_id')->nullable();
            $table->foreign('prenda_id')->references('id')->on('prendas');
            $table->unsignedBigInteger('prenda_id')->nullable();
            $table->text('descripcion')->nullable();

            $table->decimal('cantidad_prenda',12,2)->nullable();
            $table->decimal('peso_kg',12,2)->nullable();
            $table->decimal('peso_g',12,2)->nullable();

            $table->decimal('mano_obra',12,2)->nullable();
            $table->decimal('servicio_basico',12,2)->nullable();
            $table->decimal('mantenimiento',12,2)->nullable();
            $table->decimal('interes_bancario',12,2)->nullable();
            $table->decimal('porcentaje_ganacia', 12, 2)->nullable();
            $table->decimal('precio_venta_pronosticado', 12, 2)->nullable();
            $table->decimal('precio_venta_pronosticado_s3', 12, 2)->nullable();

            $table->decimal('costo_s1',12,2)->nullable();
            $table->decimal('costo_s2', 12, 2)->nullable();
            $table->decimal('costo_s3', 12, 2)->nullable();

            $table->decimal('precio_s1',12,2)->nullable();
            $table->decimal('precio_s2', 12, 2)->nullable();
            $table->decimal('precio_s3', 12, 2)->nullable();

            $table->decimal('utilidad_s1',12,2)->nullable();
            $table->decimal('utilidad_s2', 12, 2)->nullable();
            $table->decimal('utilidad_s3', 12, 2)->nullable();

            $table->decimal('porcentaje_ganancia_s1', 12, 2)->nullable();
            $table->decimal('porcentaje_ganancia_s2', 12, 2)->nullable();
            $table->decimal('porcentaje_ganancia_s3', 12, 2)->nullable();

            $table->decimal('utilidad_pronosticada_s1', 12, 2)->nullable();
            $table->decimal('utilidad_pronosticada_s2', 12, 2)->nullable();
            $table->decimal('utilidad_pronosticada_s3', 12, 2)->nullable();

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
