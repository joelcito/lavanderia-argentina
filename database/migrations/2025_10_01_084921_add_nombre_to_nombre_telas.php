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
        Schema::table('nombre_telas', function (Blueprint $table) {
            $table->unsignedBigInteger('usuario_creador_id')->nullable()->after('id');
            $table->foreign('usuario_creador_id')->references('id')->on('users');
            $table->unsignedBigInteger('usuario_modificador_id')->nullable()->after('usuario_creador_id');
            $table->foreign('usuario_modificador_id')->references('id')->on('users');
            $table->unsignedBigInteger('usuario_eliminador_id')->nullable()->after('usuario_modificador_id');
            $table->foreign('usuario_eliminador_id')->references('id')->on('users');

            $table->string('nombre')->nullable()->after('usuario_eliminador_id');
            
            $table->string('estado')->nullable()->after('nombre');
            $table->dateTime('deleted_at')->nullable()->after('estado');;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nombre_telas', function (Blueprint $table) {
            $table->dropForeign(['usuario_creador_id']);
            $table->dropColumn('usuario_creador_id');
            $table->dropForeign(['usuario_modificador_id']);
            $table->dropColumn('usuario_modificador_id');
            $table->dropForeign(['usuario_eliminador_id']);
            $table->dropColumn('usuario_eliminador_id');

            $table->dropColumn('nombre');

            $table->dropColumn('estado');
            $table->dropColumn('deleted_at');
        });
    }
};
