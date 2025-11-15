<div class="p-6">
    <h2 class="text-2xl font-semibold mb-6">
        Gestionar Pedido #{{ $order->id }}
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

            {{-- Tarjeta de Estado Actual --}}
            <div class="card bg-base-100 shadow">
                <div class="card-body">
                    <h3 class="card-title">Estado Actual</h3>
                    @switch($order->status)
                        @case('pendiente_recojo')
                            <div class="badge badge-warning badge-lg p-4">Pendiente de Recojo</div>
                            @break
                        @case('en_lavanderia')
                            <div class="badge badge-info badge-lg p-4">En Lavandería</div>
                            @break
                        @case('listo_pago')
                            <div class="badge badge-accent badge-lg p-4">Listo para Pago</div>
                            @break
                        @case('listo_entrega')
                            <div class="badge badge-info badge-lg p-4">Listo para Entrega</div>
                            @break
                        @case('completado')
                            <div class="badge badge-success badge-lg p-4">Completado</div>
                            @break
                        @case('cancelado')
                            <div class="badge badge-error badge-lg p-4">Cancelado</div>
                            @break
                    @endswitch
                </div>
            </div>

            {{-- Tarjeta de Resumen del Carrito --}}
            <div class="card bg-base-100 shadow">
                <div class="card-body">
                    <h3 class="card-title mb-4">Detalle del Pedido (Carrito)</h3>
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

            {{-- ¡¡TARJETA DE ACCIONES (NUEVA)!! --}}
            <div class="card bg-base-100 shadow sticky top-6">
                <div class="card-body">
                    <h3 class="card-title">Gestionar Pedido</h3>
                    
                    {{-- Formulario para cambiar estado --}}
                    <form wire:submit.prevent="updateStatus" class="space-y-4">
                        <div class="form-control w-full">
                            <label for="newStatus" class="label"><span class="label-text">Cambiar Estado del Pedido</span></label>
                            <select wire:model="newStatus" id="newStatus" class="select select-bordered w-full">
                                <option value="pendiente_recojo">Pendiente de Recojo</option>
                                <option value="en_lavanderia">En Lavandería</option>
                                <option value="listo_pago">Listo para Pago</option>
                                <option value="listo_entrega">Listo para Entrega</option>
                                <option value="completado">Completado</option>
                                <option value="cancelado">Cancelado</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-full">
                            Actualizar Estado
                            <span wire:loading wire:target="updateStatus" class="loading loading-spinner loading-xs"></span>
                        </button>
                    </form>

                    <div class="divider">Logística</div>
                    
                    {{-- Formulario para asignar repartidores (¡ACTIVADO!) --}}
                    <form wire:submit.prevent="assignRepartidores" class="space-y-4">
                        
                        {{-- Repartidor de RECOJO --}}
                        <div class="form-control w-full">
                            <label for="pickup_repartidor" class="label"><span class="label-text">Asignar Repartidor (Recojo)</span></label>
                            <select 
                                id="pickup_repartidor" 
                                wire:model="pickup_repartidor_id" 
                                class="select select-bordered w-full">
                                <option value="">Sin asignar</option>
                                @foreach($repartidores as $repartidor)
                                    <option value="{{ $repartidor->id }}">{{ $repartidor->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        {{-- Repartidor de ENTREGA --}}
                        <div class="form-control w-full">
                            <label for="delivery_repartidor" class="label"><span class="label-text">Asignar Repartidor (Entrega)</span></label>
                            <select 
                                id="delivery_repartidor" 
                                wire:model="delivery_repartidor_id" 
                                class="select select-bordered w-full">
                                <option value="">Sin asignar</option>
                                @foreach($repartidores as $repartidor)
                                    <option value="{{ $repartidor->id }}">{{ $repartidor->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-secondary w-full">
                            Asignar Repartidores
                            <span wire:loading wire:target="assignRepartidores" class="loading loading-spinner loading-xs"></span>
                        </button>
                    </form>
                    {{-- FIN DEL FORMULARIO ACTIVADO --}}
                    
                </div>
            </div>

            {{-- Tarjeta de Información del Cliente --}}
            <div class="card bg-base-100 shadow">
                <div class="card-body">
                    <h3 class="card-title">Datos del Cliente</h3>
                    <div class="space-y-2">
                        <div>
                            <span class="font-semibold text-sm">Nombre:</span>
                            <p>{{ $order->user->name ?? 'Usuario eliminado' }}</p>
                        </div>
                        <div>
                            <span class="font-semibold text-sm">Email:</span>
                            <p>{{ $order->user->email ?? 'N/A' }}</p>
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

            <a href="{{ route('admin.orders.index') }}" class="btn btn-ghost" wire:navigate>
                &larr; Volver a todos los pedidos
            </a>
        </div>
    </div>
</div>