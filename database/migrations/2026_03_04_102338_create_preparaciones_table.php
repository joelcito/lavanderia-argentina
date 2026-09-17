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
        Schema::create('preparaciones', function (Blueprint $table) {
            $table->id();
            $table->foreign('usuario_creador_id')->references('id')->on('users');
            $table->unsignedBigInteger('usuario_creador_id')->nullable();
            $table->foreign('usuario_modificador_id')->references('id')->on('users');
            $table->unsignedBigInteger('usuario_modificador_id')->nullable();
            $table->foreign('usuario_eliminador_id')->references('id')->on('users');
            $table->unsignedBigInteger('usuario_eliminador_id')->nullable();

            $table->foreign('solicitud_id_padre')->references('id')->on('solicitudes');
            $table->unsignedBigInteger('solicitud_id_padre')->nullable();
            $table->foreign('solicitud_id_preceso')->references('id')->on('solicitudes');
            $table->unsignedBigInteger('solicitud_id_preceso')->nullable();
            $table->foreign('proceso_id')->references('id')->on('procesos');
            $table->unsignedBigInteger('proceso_id')->nullable();
            $table->decimal('cantidad',12,2)->nullable();
            $table->decimal('cantidad_liquido', 12, 2)->nullable();
            $table->json('ordenes_trabajo')->nullable();

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
        Schema::dropIfExists('preparaciones');
    }
};
