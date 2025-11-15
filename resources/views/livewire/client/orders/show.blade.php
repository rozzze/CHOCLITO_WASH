<div class="p-6">
    <h2 class="text-2xl font-semibold mb-6">
        Detalle del Pedido #{{ $order->id }}
    </h2>

    {{-- 1. MENSAJES DE ALERTA --}}
    @if (session()->has('success'))
        <div role="alert" class="alert alert-success mb-4 shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if (session()->has('error'))
        <div role="alert" class="alert alert-error mb-4 shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif
    
    {{-- Contenedor principal de detalles --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- Columna Izquierda (Detalles y Carrito) --}}
        <div class="md:col-span-2 space-y-6">

            {{-- Tarjeta de Estado --}}
            <div class="card bg-base-100 shadow">
                <div class="card-body">
                    <h3 class="card-title">Estado del Pedido</h3>
                    @switch($order->status)
                        @case('pendiente_recojo')
                            <div class="badge badge-warning badge-lg p-4">Pendiente de Recojo</div>
                            <p>Hemos recibido tu pedido. Un repartidor pasará en la fecha y hora programada.</p>
                            @break
                        @case('en_lavanderia')
                            <div class="badge badge-info badge-lg p-4">En Lavandería</div>
                            <p>Tu ropa ya está en nuestras manos y estamos trabajando en ella.</p>
                            @break
                        @case('listo_pago')
                            <div class="badge badge-accent badge-lg p-4">¡Listo para Pago!</div>
                            <p>Tu pedido está limpio y listo. Realiza el pago para programar la entrega.</p>
                            @break
                        @case('listo_entrega')
                            <div class="badge badge-info badge-lg p-4">Listo para Entrega</div>
                            <p>Recibimos tu pago. Un repartidor te entregará el pedido en la fecha y hora programada.</p>
                            @break
                        @case('completado')
                            <div class="badge badge-success badge-lg p-4">Completado</div>
                            <p>¡Gracias por confiar en Choclito Wash!</p>
                            @break
                        @case('cancelado')
                            <div class="badge badge-error badge-lg p-4">Cancelado</div>
                            <p>Este pedido fue cancelado.</p>
                            @break
                    @endswitch
                </div>
            </div>

            {{-- Tarjeta de Resumen del Carrito --}}
            <div class="card bg-base-100 shadow">
                <div class="card-body">
                    <h3 class="card-title mb-4">Resumen del Pedido</h3>
                    <div class="overflow-x-auto">
                        <table class="table w-full">
                            <thead>
                                <tr>
                                    <th>Servicio</th>
                                    <th>Cantidad</th>
                                    <th>Precio Unit.</th>
                                    <th class="text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->services as $service)
                                    <tr>
                                        <td>{{ $service->name }}</td>
                                        <td>{{ $service->pivot->quantity }} ({{ $service->price_type }})</td>
                                        <td>S/ {{ number_format($service->pivot->price, 2) }}</td>
                                        <td class="text-right">S/ {{ number_format($service->pivot->quantity * $service->pivot->price, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            {{-- Total --}}
                            <tfoot>
                                <tr class="font-bold text-lg">
                                    <td colspan="3" class="text-right">Total:</td>
                                    <td class="text-right">S/ {{ number_format($order->total_amount, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Columna Derecha (Info y Acciones) --}}
        <div class="space-y-6">
            {{-- Tarjeta de Información --}}
            <div class="card bg-base-100 shadow">
                <div class="card-body">
                    <h3 class="card-title">Información</h3>
                    <div class="space-y-2">
                        <div>
                            <span class="font-semibold text-sm">Fecha de Pedido:</span>
                            <p>{{ $order->created_at->format('d/m/Y h:ia') }}</p>
                        </div>
                        <div>
                            <span class="font-semibold text-sm">Dirección de Recojo/Entrega:</span>
                            @if ($order->address)
                                <p class="font-bold">{{ $order->address->alias }}</p>
                                <p>{{ $order->address->street }}, {{ $order->address->district }}</p>
                                <p class="text-sm opacity-70">{{ $order->address->reference }}</p>
                            @else
                                <p class="text-error">Dirección eliminada.</p>
                            @endif
                        </div>
                        <div>
                            <span class="font-semibold text-sm">Recojo Programado:</span>
                            <p>{{ $order->scheduled_pickup_at->format('d/m/Y h:ia') }}</p>
                        </div>
                        <div>
                            <span class="font-semibold text-sm">Entrega Programada:</span>
                            <p>{{ $order->scheduled_delivery_at->format('d/m/Y h:ia') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tarjeta de Acciones --}}
            <div class="card bg-base-100 shadow">
                <div class="card-body">
                    <h3 class="card-title">Acciones</h3>
                    
                    {{-- LÓGICA CONDICIONAL DE BOTONES --}}
                    
                    @if ($order->status === 'pendiente_recojo')
                        <p class="text-sm opacity-70 mb-4">Puedes cancelar tu pedido antes de que sea recogido.</p>
                        <button 
                            type="button" 
                            class="btn btn-error" 
                            wire:click="confirmCancel({{ $order->id }})">
                            Cancelar Pedido
                        </button>
                    @endif

                    {{-- ¡¡ESTE ES EL BLOQUE CORREGIDO!! --}}
                    @if ($order->status === 'listo_pago')
                        <p class="text-sm opacity-70 mb-4">Tu pedido está limpio y listo. Realiza el pago para programar la entrega.</p>
                        <button 
                            class="btn btn-primary"
                            wire:click="pagarPedido"
                            wire:loading.attr="disabled"
                            wire:target="pagarPedido">
                            <span wire:loading.remove wire:target="pagarPedido">
                                Pagar S/ {{ number_format($order->total_amount, 2) }}
                            </span>
                            <span wire:loading wire:target="pagarPedido" class="loading loading-spinner loading-xs"></span>
                            <span wire:loading wire:target="pagarPedido">Procesando...</span>
                        </button>
                    @endif
                    {{-- FIN DEL BLOQUE CORREGIDO --}}

                    @if (in_array($order->status, ['en_lavanderia', 'listo_entrega', 'completado', 'cancelado']))
                         <p class="text-sm opacity-70">No hay acciones disponibles en este momento.</p>
                    @endif

                </div>
            </div>

            <a href="{{ route('client.orders.index') }}" class="btn btn-ghost" wire:navigate>
                &larr; Volver a Mis Pedidos
            </a>
        </div>
    </div>


    {{-- MODAL DE CONFIRMACIÓN DE CANCELACIÓN --}}
    <dialog id="cancelConfirmModal" class="modal" wire:ignore>
        <div class="modal-box">
            <h3 class="font-bold text-lg">Confirmar Cancelación</h3>
            <p class="py-4">¿Estás seguro de que deseas cancelar este pedido?</p>
            <div class="modal-action">
                <button 
                    type="button" 
                    wire:click="cancelOrder" 
                    class="btn btn-error"
                    wire:loading.attr="disabled"
                    wire:target="cancelOrder">
                    <span wire:loading.remove wire:target="cancelOrder">Sí, cancelar</span>
                    <span wire:loading wire:target="cancelOrder" class="loading loading-spinner loading-xs"></span>
                    <span wire:loading wire:target="cancelOrder">Cancelando...</span>
                </button>
                <form method="dialog">
                    <button class="btn btn-ghost">No</button>
                </form>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>cerrar</button>
        </form>
    </dialog>

    {{-- SCRIPT PARA EL MODAL --}}
    @script
    <script>
        $wire.on('open-modal', (event) => {
            const modalId = event.id || event[0]?.id;
            if (modalId === 'cancelConfirmModal') {
                const modal = document.getElementById('cancelConfirmModal');
                if (modal) {
                    modal.showModal();
                }
            }
        });

        $wire.on('close-modal', (event) => {
            const modalId = event.id || event[0]?.id;
            if (modalId === 'cancelConfirmModal') {
                const modal = document.getElementById('cancelConfirmModal');
                if (modal) {
                    modal.close();
                }
            }
        });
    </script>
    @endscript

</div>