<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::dropIfExists('tipos_procesos');
    }

    public function down(): void
    {
        // Si quisieras restaurarla podrías recrear las columnas aquí,
        // pero como fue un error normalmente se deja vacío o solo el comentario.
    }
};

