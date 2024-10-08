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
        Schema::create('prestamos', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->double('valor_prestado');
            $table->integer('cuotas');
            $table->integer('porcentaje');
            $table->string('total');
            $table->foreignId('producto_id')->constrained()->onDelete('cascade');//producto_id debe ser un valor válido que existe en la tabla productos.
            $table->foreignId('cliente_id')->constrained()->onDelete('cascade');
            $table->boolean('disponible')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestamos');
    }
};
