<div class="p-6 space-y-8">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard {{ ucfirst($role) }}</h1>
            <p class="text-gray-500 dark:text-gray-400">Bienvenido de nuevo, {{ auth()->user()->name }}.</p>
        </div>
        <div class="text-sm text-gray-500">
            {{ now()->toFormattedDateString() }}
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($stats as $key => $value)
            <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow rounded-lg border border-gray-200 dark:border-zinc-700">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                    {{ ucfirst(str_replace('_', ' ', $key)) }}
                                </dt>
                                <dd>
                                    <div class="text-lg font-medium text-gray-900 dark:text-white">
                                        {{ is_numeric($value) ? number_format($value) : $value }}
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Charts Section --}}
    @if(!empty($charts))
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            {{-- Gráfica 1: Admin o Cliente --}}
            @if(isset($charts['orders_by_status']))
                <div class="bg-white dark:bg-zinc-900 shadow rounded-lg p-6 border border-gray-200 dark:border-zinc-700">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white mb-4">Pedidos por Estado</h3>
                    <div class="relative h-64 w-full">
                        <canvas id="ordersStatusChart"></canvas>
                    </div>
                </div>
            @endif

            {{-- Gráfica 2: Admin Revenue --}}
            @if(isset($charts['revenue_history']))
                <div class="bg-white dark:bg-zinc-900 shadow rounded-lg p-6 border border-gray-200 dark:border-zinc-700">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white mb-4">Ingresos (Últimos 6 meses)</h3>
                    <div class="relative h-64 w-full">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            @endif

            {{-- Gráfica Cliente --}}
            @if(isset($charts['spending_history']))
                <div class="bg-white dark:bg-zinc-900 shadow rounded-lg p-6 border border-gray-200 dark:border-zinc-700">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white mb-4">Historial de Gastos</h3>
                    <div class="relative h-64 w-full">
                        <canvas id="spendingChart"></canvas>
                    </div>
                </div>
            @endif
        </div>
    @endif

    {{-- Chart.js Scripts with Alpine --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        document.addEventListener('livewire:navigated', () => {
             initCharts();
        });

        // Initial load
        document.addEventListener('DOMContentLoaded', () => {
             initCharts();
        });

        function initCharts() {
            // Configuración común para modo oscuro
            const isDark = document.documentElement.classList.contains('dark');
            const textColor = isDark ? '#e4e4e7' : '#374151';
            const gridColor = isDark ? '#3f3f46' : '#e5e7eb';

            Chart.defaults.color = textColor;
            Chart.defaults.borderColor = gridColor;

            // --- ADMIN: Orders Pie Chart ---
            const ctxStatus = document.getElementById('ordersStatusChart');
            if (ctxStatus) {
                // Destroy existing if needed to avoid overlay
                const existingChart = Chart.getChart(ctxStatus);
                if (existingChart) existingChart.destroy();

                new Chart(ctxStatus, {
                    type: 'doughnut',
                    data: {
                        labels: @json($charts['orders_by_status']['labels'] ?? []),
                        datasets: [{
                            data: @json($charts['orders_by_status']['data'] ?? []),
                            backgroundColor: [
                                '#6366f1', // Indigo
                                '#ec4899', // Pink
                                '#10b981', // Emerald
                                '#f59e0b', // Amber
                                '#6b7280'  // Gray
                            ],
                            borderWidth: 0
                        }]
                    },
                    options: { responsive: true, maintainAspectRatio: false }
                });
            }

            // --- ADMIN: Revenue Bar Chart ---
            const ctxRevenue = document.getElementById('revenueChart');
            if (ctxRevenue) {
                const existingChart = Chart.getChart(ctxRevenue);
                if (existingChart) existingChart.destroy();

                new Chart(ctxRevenue, {
                    type: 'bar',
                    data: {
                        labels: @json($charts['revenue_history']['labels'] ?? []),
                        datasets: [{
                            label: 'Ingresos ($)',
                            data: @json($charts['revenue_history']['data'] ?? []),
                            backgroundColor: '#8b5cf6', // Violet
                            borderRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: { beginAtZero: true }
                        }
                    }
                });
            }

            // --- CLIENT: Spending Line Chart ---
            const ctxSpending = document.getElementById('spendingChart');
            if (ctxSpending) {
                const existingChart = Chart.getChart(ctxSpending);
                if (existingChart) existingChart.destroy();

                new Chart(ctxSpending, {
                    type: 'line',
                    data: {
                        labels: @json($charts['spending_history']['labels'] ?? []),
                        datasets: [{
                            label: 'Gasto Mensual',
                            data: @json($charts['spending_history']['data'] ?? []),
                            borderColor: '#10b981',
                            tension: 0.4,
                            fill: true,
                            backgroundColor: 'rgba(16, 185, 129, 0.1)'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: { y: { beginAtZero: true } }
                    }
                });
            }
        }
    </script>
</div>
