<div class="p-6">
    <h2 class="text-2xl font-semibold mb-6">
        Gestión de Pedidos de Choclito Wash
    </h2>

    {{-- 3. TABLA DE PEDIDOS --}}
    <div class="overflow-x-auto bg-base-100 rounded-box shadow">
        <table class="table w-full">
            <thead>
                <tr>
                    <th># Pedido</th>
                    <th>Cliente</th>
                    <th>Dirección</th>
                    <th>Fecha Pedido</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th class="text-right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr wire:key="order-{{ $order->id }}" class="hover">
                        <th class="font-medium">#{{ $order->id }}</th>
                        <td>
                            {{ $order->user->name ?? 'Usuario eliminado' }}
                        </td>
                        <td>
                            {{ $order->address->alias ?? 'N/A' }}
                            <span class="text-xs opacity-60">({{ $order->address->district ?? 'N/A' }})</span>
                        </td>
                        <td>{{ $order->created_at->format('d/m/Y h:ia') }}</td>
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
                                href="{{ route('admin.orders.show', $order->id) }}"
                                wire:navigate
                                class="btn btn-ghost btn-sm">
                                Gestionar
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-8 text-gray-500">
                            No hay ningún pedido registrado todavía.
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