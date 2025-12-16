<?php

namespace App\Livewire\Admin\Reports;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Order;
use App\Models\User;
use App\Models\Service;
use Carbon\Carbon;
use Livewire\Attributes\Layout;

class Index extends Component
{
    use WithPagination;

    // Filters
    public $dateFrom;
    public $dateTo;
    public $status = '';
    public $clientId = '';
    public $repartidorId = '';
    public $minAmount = '';
    public $maxAmount = '';
    public $sortBy = 'created_at';
    public $sortDesc = true;

    // Data lists for selects
    public $statuses = [
        'pendiente', 
        'pendiente_recoleccion', 
        'recolectado', 
        'en_proceso', 
        'pendiente_entrega', 
        'completado', 
        'cancelado'
    ];

    public function mount()
    {
        // Default: Last 30 days
        $this->dateFrom = Carbon::now()->subDays(30)->format('Y-m-d');
        $this->dateTo = Carbon::now()->format('Y-m-d');
    }

    public function updated($propertyName)
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->reset(['status', 'clientId', 'repartidorId', 'minAmount', 'maxAmount']);
        $this->dateFrom = Carbon::now()->subDays(30)->format('Y-m-d');
        $this->dateTo = Carbon::now()->format('Y-m-d');
        $this->resetPage();
    }

    /* Computadas para resumen */
    public function getSummaryProperty()
    {
        // Reutilizamos la query sin paginación para sacar totales
        $query = $this->buildQuery();
        
        return [
            'total_orders' => $query->count(),
            'total_revenue' => $query->sum('total_amount'),
            'avg_ticket' => $query->avg('total_amount') ?? 0,
        ];
    }

    /* Construcción de la Query */
    protected function buildQuery()
    {
        $query = Order::query()->with(['user', 'pickupRepartidor']);

        // Date Range
        if ($this->dateFrom) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }
        if ($this->dateTo) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }

        // Status
        if ($this->status) {
            $query->where('status', $this->status);
        }

        // Client
        if ($this->clientId) {
            $query->where('user_id', $this->clientId);
        }

        // Repartidor (Pickup OR Delivery)
        if ($this->repartidorId) {
            $query->where(function ($q) {
                $q->where('pickup_repartidor_id', $this->repartidorId)
                  ->orWhere('delivery_repartidor_id', $this->repartidorId);
            });
        }

        // Amount Range
        if ($this->minAmount !== '') {
            $query->where('total_amount', '>=', $this->minAmount);
        }
        if ($this->maxAmount !== '') {
            $query->where('total_amount', '<=', $this->maxAmount);
        }

        return $query;
    }

    #[Layout('components.layouts.app')] 
    public function render()
    {
        // Listas para filtros
        $clients = User::role('cliente')->orderBy('name')->get();
        // Asumiendo que 'repartidor' es un rol
        $repartidores = User::role('repartidor')->orderBy('name')->get();

        // Ejecutar query con paginación
        $orders = $this->buildQuery()
            ->orderBy($this->sortBy, $this->sortDesc ? 'desc' : 'asc')
            ->paginate(15);

        return view('livewire.admin.reports.index', [
            'orders' => $orders,
            'clients' => $clients,
            'repartidores' => $repartidores,
            'summary' => $this->summary
        ]);
    }
}
