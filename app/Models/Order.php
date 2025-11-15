<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    use HasFactory;

    /**
     * Los atributos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'user_id',
        'address_id',
        'status',
        'total_amount',
        'scheduled_pickup_at',
        'scheduled_delivery_at',
        'pickup_repartidor_id',
        'delivery_repartidor_id',
    ];

    /**
     * Los atributos que deben ser casteados a tipos nativos.
     */
    protected $casts = [
        'total_amount' => 'decimal:2',
        'scheduled_pickup_at' => 'datetime',
        'scheduled_delivery_at' => 'datetime',
    ];

    // --- RELACIONES ---

    /**
     * El cliente (usuario) que hizo el pedido.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * La dirección de recojo/entrega del pedido.
     */
    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    /**
     * El repartidor (usuario) que RECOGIÓ el pedido.
     */
    public function pickupRepartidor(): BelongsTo
    {
        // Apuntamos a 'User' pero usando la llave foránea correcta
        return $this->belongsTo(User::class, 'pickup_repartidor_id');
    }

    /**
     * El repartidor (usuario) que ENTREGÓ el pedido.
     */
    public function deliveryRepartidor(): BelongsTo
    {
        // Apuntamos a 'User' pero usando la llave foránea correcta
        return $this->belongsTo(User::class, 'delivery_repartidor_id');
    }

    /**
     * Los servicios (productos) que están incluidos en este pedido.
     * Esta es la relación "muchos a muchos" a través de la tabla 'order_service'.
     */
    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'order_service')
                    ->withPivot('quantity', 'price'); // ¡¡CRÍTICO!! Para saber la cantidad y el precio
    }
}