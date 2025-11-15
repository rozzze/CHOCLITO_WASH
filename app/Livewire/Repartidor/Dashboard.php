<?php

namespace App\Livewire\Repartidor;

use App\Models\Order;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Mis Tareas')]
class Dashboard extends Component
{
    /**
     * El repartidor confirma que ha recogido el pedido.
     * Esto cambia el estado a 'en_lavanderia'.
     */
    public function confirmarRecojo(Order $order): void
    {
        // Doble chequeo de seguridad:
        // 1. ¿El pedido me pertenece (soy el repartidor de recojo)?
        // 2. ¿El estado es correcto ('pendiente_recojo')?
        if ($order->pickup_repartidor_id !== auth()->id() || $order->status !== 'pendiente_recojo') {
            session()->flash('error', 'Este pedido no se puede confirmar.');
            return;
        }

        // ¡Éxito! Actualizamos el estado.
        $order->update(['status' => 'en_lavanderia']);
        session()->flash('success', '¡Recojo confirmado! Llévalo a la lavandería.');
        
        // (Nota: No necesitamos recargar, Livewire lo hace solo)
    }

    /**
     * El repartidor confirma que ha entregado el pedido al cliente.
     * Esto cambia el estado a 'completado'.
     */
    public function confirmarEntrega(Order $order): void
    {
        // Doble chequeo de seguridad:
        // 1. ¿El pedido me pertenece (soy el repartidor de entrega)?
        // 2. ¿El estado es correcto ('listo_entrega')?
        if ($order->delivery_repartidor_id !== auth()->id() || $order->status !== 'listo_entrega') {
            session()->flash('error', 'Este pedido no se puede confirmar.');
            return;
        }

        // ¡Éxito! Cerramos el pedido.
        $order->update(['status' => 'completado']);
        session()->flash('success', '¡Entrega confirmada! Pedido completado.');
    }

    /**
     * Renderiza la vista.
     */
    public function render()
    {
        $repartidorId = auth()->id();

        // 1. Buscar todos los pedidos que este repartidor debe RECOGER
        $recojos = Order::with('user', 'address')
                        ->where('pickup_repartidor_id', $repartidorId)
                        ->where('status', 'pendiente_recojo')
                        ->latest('scheduled_pickup_at') // Ordenar por la fecha de recojo más próxima
                        ->get();
        
        // 2. Buscar todos los pedidos que este repartidor debe ENTREGAR
        // (El estado debe ser 'listo_entrega', que significa que el admin/operario ya lo marcó
        // como listo Y el cliente ya lo pagó... ¡aunque esa parte aún no la hacemos!)
        // Por ahora, cambiaremos la lógica a 'listo_pago' para poder probar.
        // ---
        // ¡ACTUALIZACIÓN! Vamos a asumir que el admin/operario lo pasa a 'listo_entrega'
        $entregas = Order::with('user', 'address')
                        ->where('delivery_repartidor_id', $repartidorId)
                        ->where('status', 'listo_entrega') // <-- El estado correcto
                        ->latest('scheduled_delivery_at') // Ordenar por la fecha de entrega más próxima
                        ->get();

        return view('livewire.repartidor.dashboard', [
            'recojos' => $recojos,
            'entregas' => $entregas,
        ]);
    }
}