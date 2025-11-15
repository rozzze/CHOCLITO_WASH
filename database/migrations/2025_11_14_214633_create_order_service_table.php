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
        // Esta es la tabla "detalle" (como en una boleta)
        Schema::create('order_service', function (Blueprint $table) {
            $table->id();

            // A qué pedido pertenece
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            
            // Qué servicio se pidió
            // Usamos 'set null' para que si borras un servicio (ej: "Planchado"),
            // el historial del pedido no se rompa.
            $table->foreignId('service_id')->nullable()->constrained('services')->onDelete('set null');

            // Cantidad (Ej: 8.5 para 8.5kg, o 5 para 5 camisas)
            $table->decimal('quantity', 8, 2);

            // El precio del servicio EN ESE MOMENTO (¡para congelarlo!)
            $table->decimal('price', 10, 2);

            // Esta tabla no necesita timestamps, la tabla 'orders' ya los tiene
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_service');
    }
};
