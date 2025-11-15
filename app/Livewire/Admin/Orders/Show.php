<?php

namespace App\Livewire\Admin\Orders;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Rule; // <-- Ya no usamos este


#[Title('Gestionar Pedido')]
class Show extends Component
{
    public Order $order;
    public Collection $repartidores;

    // --- PROPIEDADES (¡SIN ATRIBUTOS RULE!) ---
    public string $newStatus = '';
    public $pickup_repartidor_id = null;
    public $delivery_repartidor_id = null;
    // --- FIN DE PROPIEDADES ---


    /**
     * Carga el pedido, sus relaciones, y los repartidores
     */
    public function mount(Order $order)
    {
        // Cargamos el pedido con todas sus relaciones
        $this->order = $order->load('user', 'address', 'services');
        
        // Asignamos el estado actual al dropdown
        $this->newStatus = $this->order->status;

        // Cargamos la lista de usuarios con rol 'repartidor'
        $this->repartidores = User::role('repartidor')->orderBy('name')->get();

        // Cargamos los repartidores que ya están asignados al pedido
        $this->pickup_repartidor_id = $this->order->pickup_repartidor_id;
        $this->delivery_repartidor_id = $this->order->delivery_repartidor_id;
    }

    /**
     * Actualiza el estado del pedido
     */
    public function updateStatus(): void
    {
        // ¡ARREGLO 1! Validamos solo lo que necesitamos, aquí adentro.
        $validated = $this->validate([
            'newStatus' => 'required|in:pendiente_recojo,en_lavanderia,listo_pago,listo_entrega,completado,cancelado'
        ]);

        try {
            $this->order->update([
                'status' => $validated['newStatus'] // <-- Usamos el array validado
            ]);

            // Recargamos el pedido para que la vista se actualice
            $this->order->refresh();
            
            session()->flash('success', '¡Estado del pedido actualizado!');

        } catch (\Exception $e) {
            session()->flash('error', 'Hubo un error al actualizar el estado.');
        }
    }

    /**
     * Asigna los repartidores al pedido
     */
    public function assignRepartidores(): void
    {
        // ¡ARREGLO 2! Validamos solo lo que necesitamos, aquí adentro.
        $validated = $this->validate([
            'pickup_repartidor_id' => 'nullable|exists:users,id',
            'delivery_repartidor_id' => 'nullable|exists:users,id',
        ]);

        try {
            // Actualizamos el pedido con los IDs
            $this->order->update([
                'pickup_repartidor_id' => $validated['pickup_repartidor_id'] ?: null,
                'delivery_repartidor_id' => $validated['delivery_repartidor_id'] ?: null,
            ]);

            // Recargamos el pedido
            $this->order->refresh();
            
            session()->flash('success', '¡Repartidores asignados con éxito!');

        } catch (\Exception $e) {
            session()->flash('error', 'Hubo un error al asignar los repartidores.');
        }
    }

    public function render()
    {
        return view('livewire.admin.orders.show');
    }
}