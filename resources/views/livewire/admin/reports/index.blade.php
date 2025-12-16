<div class="p-6">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Reportes Avanzados</h1>
        <p class="text-gray-500 dark:text-gray-400">Analiza el rendimiento del negocio con múltiples filtros.</p>
    </div>

    {{-- Resumen Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700 p-6 flex items-center">
            <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pedidos</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($summary['total_orders']) }}</p>
            </div>
        </div>
        <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700 p-6 flex items-center">
            <div class="p-3 rounded-full bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Ingresos Totales (Selección)</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">${{ number_format($summary['total_revenue'], 2) }}</p>
            </div>
        </div>
        <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700 p-6 flex items-center">
            <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Ticket Promedio</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">${{ number_format($summary['avg_ticket'], 2) }}</p>
            </div>
        </div>
    </div>

    {{-- Filtros (Acordeón o Panel) --}}
    <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700 mb-8 overflow-hidden">
        <div class="p-4 border-b border-gray-200 dark:border-zinc-800 bg-gray-50 dark:bg-zinc-800/50 flex justify-between items-center">
            <h3 class="font-semibold text-gray-700 dark:text-gray-200 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                Filtros de Búsqueda
            </h3>
            <button wire:click="clearFilters" class="text-sm text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 font-medium cursor-pointer">
                Limpiar Filtros
            </button>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            
            {{-- Fechas --}}
            <div class="col-span-1">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fecha Inicio</label>
                <input type="date" wire:model.live="dateFrom" class="w-full rounded-lg border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <div class="col-span-1">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fecha Fin</label>
                <input type="date" wire:model.live="dateTo" class="w-full rounded-lg border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>

            {{-- Estado --}}
            <div class="col-span-1">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Estado del Pedido</label>
                <select wire:model.live="status" class="w-full rounded-lg border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="">Todos</option>
                    @foreach($statuses as $st)
                        <option value="{{ $st }}">{{ ucfirst(str_replace('_', ' ', $st)) }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Cliente --}}
            <div class="col-span-1">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cliente</label>
                <select wire:model.live="clientId" class="w-full rounded-lg border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="">Todos</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Repartidor --}}
            <div class="col-span-1">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Repartidor (Cualquiera)</label>
                <select wire:model.live="repartidorId" class="w-full rounded-lg border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="">Todos</option>
                    @foreach($repartidores as $rep)
                        <option value="{{ $rep->id }}">{{ $rep->name }}</option>
                    @endforeach
                </select>
            </div>

             {{-- Montos --}}
             <div class="col-span-1">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Monto Mín.</label>
                <div class="relative rounded-md shadow-sm">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                      <span class="text-gray-500 sm:text-sm">$</span>
                    </div>
                    <input type="number" wire:model.live.debounce.500ms="minAmount" class="pl-7 block w-full rounded-lg border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="0.00">
                </div>
            </div>
            <div class="col-span-1">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Monto Máx.</label>
                <div class="relative rounded-md shadow-sm">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                      <span class="text-gray-500 sm:text-sm">$</span>
                    </div>
                    <input type="number" wire:model.live.debounce.500ms="maxAmount" class="pl-7 block w-full rounded-lg border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="1000.00">
                </div>
            </div>

        </div>
    </div>

    {{-- Tabla de Resultados --}}
    <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-800">
                <thead class="bg-gray-50 dark:bg-zinc-800/80">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">ID</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Fecha</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Cliente</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Estado</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Monto</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-900 divide-y divide-gray-200 dark:divide-zinc-800">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50 dark:hover:bg-zinc-800/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">#{{ $order->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white font-medium">{{ $order->user->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($order->status === 'completado') bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300
                                    @elseif($order->status === 'cancelado') bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300
                                    @elseif($order->status === 'pendiente') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300
                                    @else bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-white">${{ number_format($order->total_amount, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.orders.show', $order) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 hover:underline">Ver Detalle</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                No se encontraron pedidos con los filtros seleccionados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200 dark:border-zinc-800">
            {{ $orders->links() }}
        </div>
    </div>
</div>
