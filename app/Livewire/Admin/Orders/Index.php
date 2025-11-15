<?php

namespace App\Livewire\Admin\Orders;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;

#[Title('Gestionar Pedidos')]
class Index extends Component
{
    use WithPagination;

    // ⚠️ IMPORTANTE: Define el tema de paginación para DaisyUI
    protected $paginationTheme = 'tailwind';

    public function render()
    {
        // 1. Obtenemos TODOS los pedidos (no solo los del admin)
        // 2. Usamos 'with' para cargar las relaciones 'user' y 'address'
        //    Esto evita N+1 queries y hace que la tabla cargue rápido
        $orders = Order::with('user', 'address')
                        ->latest() // Los más nuevos primero
                        ->paginate(15); // 15 por página para el admin

        return view('livewire.admin.orders.index', [
            'orders' => $orders
        ]);
    }
}