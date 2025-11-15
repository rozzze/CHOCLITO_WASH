<?php

namespace App\Livewire\Client\Orders;

use App\Models\Address;
use App\Models\Order;
use App\Models\Service;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Rule;

#[Title('Nuevo Pedido')]
class Create extends Component
{
    // --- COLECCIONES ---
    public Collection $addresses;
    public Collection $services;

    // --- FORMULARIO PRINCIPAL ---
    #[Rule('required|exists:addresses,id', message: 'Debes seleccionar una dirección.')]
    public $address_id = null;

    #[Rule('required|date|after_or_equal:now', message: 'La fecha de recojo no puede ser en el pasado.')]
    public $scheduled_pickup_at = '';
    
    #[Rule('required|date|after:scheduled_pickup_at', message: 'La fecha de entrega debe ser después del recojo.')]
    public $scheduled_delivery_at = '';

    // --- LÓGICA DEL CARRITO ---
    public array $cart = [];
    public float $total = 0.00;

    // --- CAMPOS TEMPORALES (para añadir al carrito) ---
    public $current_service_id = '';
    public $current_quantity = 1;

    /**
     * Carga los datos iniciales (direcciones y servicios)
     */
    public function mount(): void
    {
        // Cargamos solo las direcciones del cliente y los servicios activos
        $this->addresses = auth()->user()->addresses;
        $this->services = Service::where('is_active', true)->orderBy('name')->get();
        
        // --- ¡¡AQUÍ ESTÁ LA CORRECCIÓN!! ---
        // Auto-seleccionamos la primera dirección si existe.
        // Esto evita que $address_id sea 'null' al inicio.
        $this->address_id = $this->addresses->first()?->id;
        // --- FIN DE LA CORRECCIÓN ---

        $this->cart = [];
        $this->total = 0.00;
    }

    /**
     * Añade un servicio al carrito.
     */
    public function addToCart(): void
    {
// ... (El resto del archivo PHP es idéntico) ...
// ... (Validación, addToCart, removeFromCart, recalculateTotal, save) ...
// ... (No necesitas cambiar nada más en este archivo) ...
// 1. Validar los campos temporales
        $this->validate([
            'current_service_id' => 'required|exists:services,id',
            'current_quantity' => 'required|numeric|min:0.1'
        ], [
            'current_service_id.required' => 'Debes seleccionar un servicio.',
            'current_quantity.min' => 'La cantidad debe ser positiva.'
        ]);

        // 2. Comprobar si ya existe en el carrito
        if (isset($this->cart[$this->current_service_id])) {
            // Si existe, solo actualiza la cantidad
            $this->cart[$this->current_service_id]['quantity'] = $this->current_quantity;
        } else {
            // Si no, búscalo y añádelo
            $service = Service::find($this->current_service_id);
            $this->cart[$this->current_service_id] = [
                'service_id' => $service->id,
                'name' => $service->name,
                'price' => $service->price,
                'price_type' => $service->price_type,
                'quantity' => $this->current_quantity,
            ];
        }

        // 3. Resetear campos y recalcular total
        $this->reset('current_service_id', 'current_quantity');
        $this->recalculateTotal();
    }

    /**
     * Quita un ítem del carrito.
     */
    public function removeFromCart(int $serviceId): void
    {
        unset($this->cart[$serviceId]);
        $this->recalculateTotal();
    }

    /**
     * Recalcula el total del carrito.
     */
    public function recalculateTotal(): void
    {
        $this->total = 0.00;
        foreach ($this->cart as $item) {
            $this->total += $item['price'] * $item['quantity'];
        }
    }

    /**
     * ¡Guarda el pedido completo!
     */
    public function save()
    {
        // 1. Validar el formulario principal
        $this->validate();

        // 2. Validar que el carrito no esté vacío
        if (empty($this->cart)) {
            session()->flash('error_cart', 'Tu pedido está vacío. Añade al menos un servicio.');
            return;
        }

        // 3. Usar una transacción de BD (¡súper importante!)
        // Si algo falla (ej: al guardar el detalle), se revierte todo.
        try {
            DB::beginTransaction();

            // 3.1. Crear el Pedido (la "cabeza")
            $order = Order::create([
                'user_id' => auth()->id(),
                'address_id' => $this->address_id,
                'status' => 'pendiente_recojo',
                'total_amount' => $this->total,
                'scheduled_pickup_at' => $this->scheduled_pickup_at,
                'scheduled_delivery_at' => $this->scheduled_delivery_at,
            ]);

            // 3.2. Preparar el "detalle" para la tabla pivote
            $orderServices = [];
            foreach ($this->cart as $item) {
                $orderServices[$item['service_id']] = [
                    'quantity' => $item['quantity'],
                    'price' => $item['price'] // Congelamos el precio
                ];
            }

            // 3.3. Guardar el "detalle"
            $order->services()->attach($orderServices);

            // 3.4. ¡Todo salió bien!
            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Hubo un error al procesar tu pedido. Por favor, inténtalo de nuevo.');
            return;
        }

        // 4. Redirigir con mensaje de éxito
        session()->flash('success', '¡Tu pedido se ha registrado con éxito!');
        $this->redirect(route('client.orders.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.client.orders.create');
    }
}