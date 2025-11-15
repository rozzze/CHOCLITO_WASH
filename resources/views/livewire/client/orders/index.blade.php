<div class="p-6">
    <h2 class="text-2xl font-semibold mb-6">
        Mis Pedidos
    </h2>

    {{-- 1. MENSAJE DE ÉXITO (si venimos de crear un pedido) --}}
    @if (session()->has('success'))
        <div role="alert" class="alert alert-success mb-4 shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- 3. TABLA DE PEDIDOS --}}
    <div class="overflow-x-auto bg-base-100 rounded-box shadow">
        <table class="table w-full">
            <thead>
                <tr>
                    <th># Pedido</th>
                    <th>Fecha</th>
                    <th>Dirección</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th class="text-right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr wire:key="order-{{ $order->id }}" class="hover">
                        <th class="font-medium">#{{ $order->id }}</th>
                        <td>{{ $order->created_at->format('d/m/Y h:ia') }}</td>
                        <td>{{ $order->address->alias ?? 'Dirección eliminada' }}</td>
                        <td>S/ {{ number_format($order->total_amount, 2) }}</td>
                        <td>
                            {{-- Badges de estado de DaisyUI --}}
                            @switch($order->status)
                                @case('pendiente_recojo')
                                    <span class="badge badge-warning">Pendiente de Recojo</span>
                                    @break
                                @case('en_lavanderia')
                                    <span class="badge badge-info">En Lavandería</span>
                                    @break
                                @case('listo_pago')
                                    <span class="badge badge-accent">Listo para Pago</span>
                                    @break
                                @case('listo_entrega')
                                    <span class="badge badge-info">Listo para Entrega</span>
                                    @break
                                @case('completado')
                                    <span class="badge badge-success">Completado</span>
                                    @break
                                @case('cancelado')
                                    <span class="badge badge-error">Cancelado</span>
                                    @break
                            @endswitch
                        </td>
                        <td class="text-right">
                            <a 
                                href="{{ route('client.orders.show', $order->id) }}"
                                wire:navigate
                                class="btn btn-ghost btn-sm">
                                Ver Detalle
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-8 text-gray-500">
                            No has realizado ningún pedido todavía.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    {{-- 4. PAGINACIÓN --}}
    <div class="mt-4">
        {{ $orders->links() }}
    </div>
</div>