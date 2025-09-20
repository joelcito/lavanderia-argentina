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
    // opcionalmente, código para recrear la tabla si se quiere revertir
}

   
};

