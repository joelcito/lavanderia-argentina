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
        Schema::table('pagos', function (Blueprint $table) {
            // 🔹 Relación con empleado
            $table->foreignId('user_id')->nullable()->after('sucursal_id')
                ->constrained()->onDelete('set null');

            // 🔹 Periodo de pago
            $table->date('fecha_inicio')->nullable()->after('fecha');
            $table->date('fecha_fin')->nullable()->after('fecha_inicio');

            // 🔹 Datos del cálculo (histórico)
            $table->decimal('pago_diario_usado', 10, 2)->nullable();
            $table->integer('horas_base_usado')->nullable();

            $table->integer('total_horas')->nullable();
            $table->integer('total_minutos')->nullable();

            $table->decimal('monto_calculado', 10, 2)->nullable();
            $table->decimal('total_descuentos', 10, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pagos', function (Blueprint $table) {
            // 🔹 Relación con empleado
            $table->foreignId('user_id')->nullable()->after('sucursal_id')
                ->constrained()->onDelete('set null');

            // 🔹 Periodo de pago
            $table->date('fecha_inicio')->nullable()->after('fecha');
            $table->date('fecha_fin')->nullable()->after('fecha_inicio');

            // 🔹 Datos del cálculo (histórico)
            $table->decimal('pago_diario_usado', 10, 2)->nullable();
            $table->integer('horas_base_usado')->nullable();

            $table->integer('total_horas')->nullable();
            $table->integer('total_minutos')->nullable();

            $table->decimal('monto_calculado', 10, 2)->nullable();
            $table->decimal('total_descuentos', 10, 2)->nullable();
        });
    }
};
