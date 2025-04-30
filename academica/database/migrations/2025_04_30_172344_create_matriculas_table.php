<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('matriculas', function (Blueprint $table) {
        $table->id();
        $table->foreignId('idAlumno')->constrained('alumnos')->onDelete('cascade');
        $table->string('anio', 4);
        $table->string('ciclo', 10);
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matriculas');
    }
};
