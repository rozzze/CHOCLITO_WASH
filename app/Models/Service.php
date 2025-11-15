<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany; // <-- AÑADIDO

class Service extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'price_type',
        'price',
        'is_active',
    ];

    // --- ¡¡ESTO ES LO QUE AÑADIMOS!! ---
            
    /**
     * Define la relación: un servicio puede estar en muchos pedidos.
     */
    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'order_service')
                    ->withPivot('quantity', 'price'); // ¡¡CRÍTICO!!
    }
}