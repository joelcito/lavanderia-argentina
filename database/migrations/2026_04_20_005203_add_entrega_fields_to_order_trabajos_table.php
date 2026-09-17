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
        Schema::table('order_trabajos', function (Blueprint $table) {
            $table->dateTime('fecha_entrega')->nullable()->after('estado');
            $table->string('entregado_a')->nullable()->after('fecha_entrega');
            //
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_trabajos', function (Blueprint $table) {
            $table->dropColumn(['fecha_entrega', 'entregado_a']);
            //
        });
    }
};
