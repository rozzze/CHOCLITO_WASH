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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // --- CLAVES FORÁNEAS ---
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('address_id')->nullable()->constrained('addresses')->onDelete('set null');

            // --- ESTADO Y PRECIO ---
            $table->enum('status', [
                'pendiente_recojo',
                'en_lavanderia',
                'listo_pago',
                'listo_entrega',
                'completado',
                'cancelado'
            ])->default('pendiente_recojo');
            
            $table->decimal('total_amount', 10, 2);

            // --- FECHAS PROGRAMADAS (¡CORREGIDO!) ---
            $table->timestamp('scheduled_pickup_at')->nullable();  // <-- AÑADIDO
            $table->timestamp('scheduled_delivery_at')->nullable(); // <-- AÑADIDO

            // --- LOGÍSTICA (REPARTIDORES) ---
            $table->foreignId('pickup_repartidor_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('delivery_repartidor_id')->nullable()->constrained('users')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
