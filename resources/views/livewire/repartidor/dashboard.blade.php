<div class="p-6">
    <h2 class="text-2xl font-semibold mb-6">
        Mis Tareas Pendientes
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

    {{-- Contenedor de Tareas --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        {{-- COLUMNA 1: RECOJOS PENDIENTES --}}
        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                <h3 class="card-title text-warning">1. Recojos Pendientes ({{ $recojos->count() }})</h3>
                <p>Pedidos que debes recoger del cliente.</p>

                <div class="overflow-x-auto mt-4">
                    <table class="table table-zebra w-full">
                        <thead>
                            <tr>
                                <th>Pedido</th>
                                <th>Cliente / Dirección</th>
                                <th>Hora Programada</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recojos as $recojo)
                                <tr wire:key="recojo-{{ $recojo->id }}">
                                    <td>#{{ $recojo->id }}</td>
                                    <td>
                                        <div class="font-bold">{{ $recojo->user->name ?? 'N/A' }}</div>
                                        <div class="text-sm opacity-70">{{ $recojo->address->street ?? 'N/A' }}</div>
                                    </td>
                                    <td>
                                        <div class="font-bold">{{ $recojo->scheduled_pickup_at->format('h:ia') }}</div>
                                        <div class="text-sm opacity-70">{{ $recojo->scheduled_pickup_at->format('d/m/Y') }}</div>
                                    </td>
                                    <td>
                                        <button 
                                            class="btn btn-warning btn-sm"
                                            wire:click="confirmarRecojo({{ $recojo->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="confirmarRecojo({{ $recojo->id }})">
                                            Confirmar Recojo
                                            <span wire:loading wire:target="confirmarRecojo({{ $recojo->id }})" class="loading loading-spinner loading-xs"></span>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">¡No tienes recojos pendientes!</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- COLUMNA 2: ENTREGAS PENDIENTES --}}
        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                <h3 class="card-title text-info">2. Entregas Pendientes ({{ $entregas->count() }})</h3>
                <p>Pedidos listos y pagados que debes entregar al cliente.</p>

                <div class="overflow-x-auto mt-4">
                    <table class="table table-zebra w-full">
                        <thead>
                            <tr>
                                <th>Pedido</th>
                                <th>Cliente / Dirección</th>
                                <th>Hora Programada</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                             @forelse($entregas as $entrega)
                                <tr wire:key="entrega-{{ $entrega->id }}">
                                    <td>#{{ $entrega->id }}</td>
                                    <td>
                                        <div class="font-bold">{{ $entrega->user->name ?? 'N/A' }}</div>
                                        <div class="text-sm opacity-70">{{ $entrega->address->street ?? 'N/A' }}</div>
                                    </td>
                                    <td>
                                        <div class="font-bold">{{ $entrega->scheduled_delivery_at->format('h:ia') }}</div>
                                        <div class="text-sm opacity-70">{{ $entrega->scheduled_delivery_at->format('d/m/Y') }}</div>
                                    </td>
                                    <td>
                                        <button 
                                            class="btn btn-info btn-sm"
                                            wire:click="confirmarEntrega({{ $entrega->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="confirmarEntrega({{ $entrega->id }})">
                                            Confirmar Entrega
                                            <span wire:loading wire:target="confirmarEntrega({{ $entrega->id }})" class="loading loading-spinner loading-xs"></span>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">¡No tienes entregas pendientes!</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

</div>