<?php

namespace App\Livewire\Client\Orders;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;

#[Title('Mis Pedidos')]
class Index extends Component
{
    use WithPagination;

    // ⚠️ IMPORTANTE: Define el tema de paginación para DaisyUI
    protected $paginationTheme = 'tailwind';

    /**
     * Renderiza la vista con los pedidos del usuario
     */
    public function render()
    {
        // 1. Obtenemos los pedidos SOLO del usuario autenticado
        // 2. Usamos 'with' para cargar la dirección (Eager Loading) y evitar N+1
        // 3. 'latest()' para ordenar (el más nuevo primero)
        $orders = auth()->user()
                        ->orders() 
                        ->with('address') 
                        ->latest()
                        ->paginate(10);

        return view('livewire.client.orders.index', [
            'orders' => $orders
        ]);
    }
}