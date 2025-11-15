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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            
            // ¡La columna más importante!
            // Vincula esta dirección con un usuario.
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->string('alias', 100);       // Ej: "Casa", "Oficina"
            $table->string('street', 255);      // Ej: "Av. Sol 123"
            $table->string('district', 100);    // Ej: "Yanahuara"
            $table->string('reference', 255)->nullable(); // Ej: "Puerta roja"
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
