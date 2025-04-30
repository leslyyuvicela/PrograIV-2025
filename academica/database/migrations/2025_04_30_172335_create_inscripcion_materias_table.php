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
    Schema::create('inscripcion_materias', function (Blueprint $table) {
        $table->id();
        $table->foreignId('idAlumno')->constrained('alumnos')->onDelete('cascade');
        $table->foreignId('idMateria')->constrained('materias')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inscripcion_materias');
    }
};
