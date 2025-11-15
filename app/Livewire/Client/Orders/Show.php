<?php

namespace App\Livewire\Client\Orders;

use App\Models\Order;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Detalle de Pedido')]
class Show extends Component
{
    public Order $order;
    public ?int $cancelingId = null;

    /**
     * Carga el pedido y verifica que pertenezca al usuario.
     */
    public function mount(Order $order)
    {
        // ¡¡SEGURIDAD CRUCIAL!!
        // Si el ID del usuario del pedido NO es el ID del usuario logueado
        if ($order->user_id !== auth()->id()) {
            abort(403); // Prohibido
        }

        // Carga la data del pedido (incluyendo el detalle)
        $this->order = $order->load('address', 'services');
    }

    /**
     * Prepara el ID para el modal de confirmación de cancelación
     */
    public function confirmCancel(int $id): void
    {
        $this->cancelingId = $id;
        $this->dispatch('open-modal', id: 'cancelConfirmModal');
    }

    /**
     * Cambia el estado del pedido a 'cancelado'
     */
    public function cancelOrder(): void
    {
        if (!$this->cancelingId) {
            return;
        }

        // Solo se pueden cancelar pedidos que están pendientes
        if ($this->order->status === 'pendiente_recojo') {
            $this->order->update(['status' => 'cancelado']);
            
            // Recargamos el pedido para que la vista se actualice
            $this->order->refresh();
            
            session()->flash('success', '¡Pedido cancelado con éxito!');
        } else {
            session()->flash('error', 'Ya no es posible cancelar este pedido.');
        }

        $this->dispatch('close-modal', id: 'cancelConfirmModal');
        $this->cancelingId = null;
    }

    // --- ¡¡AQUÍ ESTÁ LA NUEVA FUNCIÓN!! ---
    /**
     * Simula un pago exitoso.
     * Cambia el estado de 'listo_pago' a 'listo_entrega'.
     */
    public function pagarPedido(): void
    {
        // 1. Verificación de seguridad
        if ($this->order->status !== 'listo_pago') {
            session()->flash('error', 'Este pedido no está listo para ser pagado.');
            return;
        }

        // 2. Simulación de pago exitoso (aquí iría Culqi/Stripe)
        // ...
        // ... (pago exitoso) ...

        // 3. Actualizamos el estado
        try {
            $this->order->update([
                'status' => 'listo_entrega'
            ]);

            // Recargamos el pedido para que la vista se actualice
            $this->order->refresh();
            
            session()->flash('success', '¡Pago realizado con éxito! Tu pedido está en camino.');

        } catch (\Exception $e) {
            session()->flash('error', 'Hubo un error al procesar el pago.');
        }
    }
    // --- FIN DE LA NUEVA FUNCIÓN ---


    /**
     * Renderiza la vista
     */
    public function render()
    {
        return view('livewire.client.orders.show');
    }
}