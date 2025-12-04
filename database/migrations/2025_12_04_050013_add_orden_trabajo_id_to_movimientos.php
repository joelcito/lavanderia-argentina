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
        Schema::table('movimientos', function (Blueprint $table) {
            $table->unsignedBigInteger('orden_trabajo_id')->nullable()->after('sucursal_id');
            $table->foreign('orden_trabajo_id')->references('id')->on('order_trabajos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movimientos', function (Blueprint $table) {
            $table->dropForeign(['orden_trabajo_id']);
            $table->dropColumn('orden_trabajo_id');
        });
    }
};
