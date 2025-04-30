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
    Schema::create('donaciones', function (Blueprint $table) {
        $table->id();
        $table->enum('tipo', ['bien', 'servicio']);
        $table->text('descripcion');
        $table->decimal('valor_estimado', 10, 2)->nullable();
        $table->date('fecha_recepcion');
        $table->foreignId('donante_id')->constrained('donantes')->onDelete('cascade');
        $table->foreignId('administrador_id')->constrained('administradores')->onDelete('cascade');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donacions');
    }
};
