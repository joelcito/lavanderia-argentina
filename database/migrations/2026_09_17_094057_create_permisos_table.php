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
        Schema::create('permisos', function (Blueprint $table) {
            $table->id();

            // Sirve para agrupar visualmente los permisos.
            // Ej: Administración, Lavandería, Caja, Reportes
            $table->string('grupo');

            // Recurso o submenú.
            // Ej: Usuarios, Roles, Sucursales
            $table->string('modulo');

            // Nombre mostrado al administrador.
            // Ej: Ver usuarios
            $table->string('nombre');

            // Nombre utilizado por Laravel Gate.
            // Ej: usuarios.ver
            $table->string('codigo')->unique();

            // ver, crear, editar, eliminar...
            $table->string('accion');

            $table->string('estado')->default('ACTIVO');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permisos');
    }
};
