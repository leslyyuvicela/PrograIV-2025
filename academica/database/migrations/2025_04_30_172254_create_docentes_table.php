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
    Schema::create('docentes', function (Blueprint $table) {
        $table->id();
        $table->string('codigo', 50)->unique();
        $table->string('nombre', 150);
        $table->string('direccion', 255);
        $table->string('telefono', 20)->nullable();
        $table->string('email', 100)->nullable();
        $table->uuid('codigo_transaccion')->nullable();
        $table->string('hash', 255)->nullable();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('docentes');
    }
};
