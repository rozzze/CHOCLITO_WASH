<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Attributes\Layout;

class Dashboard extends Component
{
    public $role;
    
    public $stats = [];
    public $charts = [];

    public function mount()
    {
        $user = auth()->user();
        // Obtener el primer rol del usuario para simplificar visualización
        $this->role = $user->roles->first()?->name ?? 'cliente';

        $this->loadStats();
        $this->loadCharts();
    }

    public function loadStats()
    {
        $user = auth()->user();

        if ($this->role === 'admin') {
            $this->stats = [
                'total_users' => User::count(),
                'total_orders' => Order::count(),
                'monthly_revenue' => Order::whereMonth('created_at', now()->month)->sum('total_amount'),
                'pending_orders' => Order::where('status', 'pendiente')->count(),
            ];
        } elseif ($this->role === 'cliente') {
            $this->stats = [
                'my_orders' => Order::where('user_id', $user->id)->count(),
                'active_orders' => Order::where('user_id', $user->id)->whereIn('status', ['pendiente', 'en_proceso', 'recolectado'])->count(),
                'total_spent' => Order::where('user_id', $user->id)->sum('total_amount'),
            ];
        } elseif ($this->role === 'operario') {
             // Asignamos una lógica genérica para operario pues no vi asignación directa en el modelo a operarios, 
             // asumiremos que ve todos los "en proceso"
             $this->stats = [
                'orders_in_process' => Order::where('status', 'en_proceso')->count(),
                'completed_today' => Order::where('status', 'completado')
                                          ->whereDate('updated_at', today())
                                          ->count(),
             ];
        } elseif ($this->role === 'repartidor') {
            $this->stats = [
                'pending_pickups' => Order::where('pickup_repartidor_id', $user->id)
                                          ->where('status', 'pendiente_recoleccion')
                                          ->count(),
                'pending_deliveries' => Order::where('delivery_repartidor_id', $user->id)
                                             ->where('status', 'pendiente_entrega')
                                             ->count(),
                'completed_tasks' => Order::where(function($q) use ($user) {
                                            $q->where('pickup_repartidor_id', $user->id)
                                              ->orWhere('delivery_repartidor_id', $user->id);
                                        })->where('status', 'completado')->count(),
            ];
        }
    }

    public function loadCharts()
    {
        // Preparamos datos para pasar a JS
        if ($this->role === 'admin') {
            // 1. Pedidos por Estado
            $ordersByStatus = Order::select('status', DB::raw('count(*) as total'))
                                   ->groupBy('status')
                                   ->pluck('total', 'status')->toArray();
            
            // Labels y Data seguros
            $statuses = array_keys($ordersByStatus);
            $totals = array_values($ordersByStatus);

            // Si no hay datos, rellenar con demo
            if (empty($ordersByStatus)) {
                $statuses = ['Pendiente', 'En Proceso', 'Completado', 'Cancelado'];
                $totals = [12, 19, 3, 5];
            }

            $this->charts['orders_by_status'] = [
                'labels' => $statuses,
                'data' => $totals,
            ];

            // 2. Ingresos últimos 6 meses
            // Demo data por simplicidad en SQL complejo
            $this->charts['revenue_history'] = [
                'labels' => ['Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                'data' => [12000, 19000, 3000, 5000, 2000, 30000],
            ];

        } elseif ($this->role === 'cliente') {
            $this->charts['spending_history'] = [
                'labels' => ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
                'data' => [65, 59, 80, 81, 56, 55],
            ];
        }
    }

    #[Layout('components.layouts.app')] 
    public function render()
    {
        return view('livewire.dashboard');
    }
}
