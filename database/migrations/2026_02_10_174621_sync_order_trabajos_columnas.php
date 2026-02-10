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

            if (!Schema::hasColumn('order_trabajos', 'precio_pronosticado')) {
                $table->decimal('precio_pronosticado', 12, 3)->nullable()->after('dpi');
            }

            if (!Schema::hasColumn('order_trabajos', 'orden_trabajos')) {
                $table->json('orden_trabajos')->nullable()->after('precio_pronosticado');
            }

            if (!Schema::hasColumn('order_trabajos', 'con_muestra')) {
                $table->boolean('con_muestra')->nullable()->after('orden_trabajos');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_trabajos', function (Blueprint $table) {
            if (Schema::hasColumn('order_trabajos', 'precio_pronosticado')) {
                $table->dropColumn('precio_pronosticado');
            }
            if (Schema::hasColumn('order_trabajos', 'orden_trabajos')) {
                $table->dropColumn('orden_trabajos');
            }
            if (Schema::hasColumn('order_trabajos', 'con_muestra')) {
                $table->dropColumn('con_muestra');
            }
        });
    }
};
