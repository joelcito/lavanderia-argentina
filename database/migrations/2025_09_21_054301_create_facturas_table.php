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
        Schema::create('facturas', function (Blueprint $table) {
            $table->id();
            $table->foreign('usuario_creador_id')->references('id')->on('users');
            $table->unsignedBigInteger('usuario_creador_id')->nullable();
            $table->foreign('usuario_modificador_id')->references('id')->on('users');
            $table->unsignedBigInteger('usuario_modificador_id')->nullable();
            $table->foreign('usuario_eliminador_id')->references('id')->on('users');
            $table->unsignedBigInteger('usuario_eliminador_id')->nullable();
            //foranea clientes
            $table->foreign('usuario_cliente_id')->references('id')->on('users');
            $table->unsignedBigInteger('usuario_cliente_id')->nullable();
            //foranea usuario recepciono
            $table->foreign('usuario_recepciono_id')->references('id')->on('users');
            $table->unsignedBigInteger('usuario_recepciono_id')->nullable();
            $table->foreign('sucursal_id')->references('id')->on('sucursales');
            $table->unsignedBigInteger('sucursal_id')->nullable();

            $table->dateTime('fecha')->nullable();
            $table->string('nit')->nullable();
            $table->string('razon_social')->nullable();
            $table->string('numero_factura')->nullable();
            $table->decimal('total',12,2)->nullable();
            $table->decimal('descuento_adicional',12,2)->nullable();
            $table->text('descripcion')->nullable();
            $table->string('estado_pago')->nullable();
            $table->string('prioridad')->nullable();
            $table->dateTime('fecha_recepcion')->nullable();
            $table->string('servico_laser',2)->nullable();
            $table->string('entregado_por')->nullable();
            $table->string('preceso_lavado',2)->nullable();
            $table->string('estado_venta')->nullable();

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
        Schema::dropIfExists('facturas');
    }
};
