<div class="p-6">
    <h2 class="text-2xl font-semibold mb-6">
        Crear Nuevo Pedido
    </h2>

    {{-- Mensaje de Error General --}}
    @if (session()->has('error'))
        <div role="alert" class="alert alert-error mb-4 shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif
    {{-- Mensaje de Error del Carrito --}}
    @if (session()->has('error_cart'))
        <div role="alert" class="alert alert-warning mb-4 shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
            <span>{{ session('error_cart') }}</span>
        </div>
    @endif

    {{-- Usamos un 'form' que llama a 'save' --}}
    <form wire:submit.prevent="save">
    
        {{-- PASO 1: DETALLES DEL PEDIDO --}}
        <div class="p-6 bg-base-100 rounded-box shadow-lg mb-6">
            <h3 class="text-xl font-semibold mb-4">1. Dinos dónde y cuándo</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                
                {{-- Selector de Dirección --}}
                <div class="form-control w-full">
                    <label for="address_id" class="label"><span class="label-text">¿Dónde lo recogemos?</span></label>
                    
                    {{-- ¡¡AQUÍ ESTÁ LA CORRECCIÓN!! --}}
                    <select 
                        wire:model.live="address_id" {{-- <- Cambiado a .live --}}
                        id="address_id" 
                        class="select select-bordered w-full @error('address_id') select-error @enderror">
                        
                        <option value="" disabled>Selecciona una dirección</option>
                        
                        @forelse ($addresses as $address)
                            <option value="{{ $address->id }}">{{ $address->alias }} ({{ $address->street }})</option>
                        @empty
                            <option value="" disabled>No tienes direcciones guardadas.</option>
                        @endforelse
                    </select>
                    @error('address_id') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                    <a href="{{ route('client.addresses.create') }}" wire:navigate class="text-sm text-primary hover:underline mt-2">¿Necesitas agregar una dirección nueva?</a>
                </div>

                <div></div> {{-- Espacio en blanco --}}

                {{-- Fecha de Recojo --}}
                <div class="form-control w-full">
                    <label for="scheduled_pickup_at" class="label"><span class="label-text">Fecha y Hora de Recojo</span></label>
                    <input type="datetime-local" id="scheduled_pickup_at" wire:model="scheduled_pickup_at" class="input input-bordered w-full @error('scheduled_pickup_at') input-error @enderror">
                    @error('scheduled_pickup_at') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                </div>

                {{-- Fecha de Entrega --}}
                <div class="form-control w-full">
                    <label for="scheduled_delivery_at" class="label"><span class="label-text">Fecha y Hora de Entrega</span></label>
                    <input type="datetime-local" id="scheduled_delivery_at" wire:model="scheduled_delivery_at" class="input input-bordered w-full @error('scheduled_delivery_at') input-error @enderror">
                    @error('scheduled_delivery_at') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                </div>
            </div>
        </div>

        {{-- PASO 2: ARMAR EL PEDIDO (CARRITO) --}}
        <div class="p-6 bg-base-100 rounded-box shadow-lg mb-6">
            <h3 class="text-xl font-semibold mb-4">2. ¿Qué lavaremos hoy?</h3>
            
            {{-- Formulario para añadir al carrito (no usa 'submit') --}}
            <div class="flex flex-col md:flex-row gap-4 items-end mb-4 p-4 border rounded-box">
                <div class="form-control w-full md:w-1/2">
                    <label for="current_service_id" class="label"><span class="label-text">Servicio</span></label>
                    <select wire:model="current_service_id" id="current_service_id" class="select select-bordered w-full @error('current_service_id') select-error @enderror">
                        <option value="" disabled>Selecciona un servicio</option>
                        @foreach ($services as $service)
                            <option value="{{ $service->id }}">{{ $service->name }} (S/ {{ $service->price }} por {{ $service->price_type }})</option>
                        @endforeach
                    </select>
                    @error('current_service_id') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                </div>
                
                <div class="form-control w-full md:w-1/4">
                    <label for="current_quantity" class="label"><span class="label-text">Cantidad (Kg o Unid.)</span></label>
                    <input type="number" id="current_quantity" wire:model="current_quantity" step="0.1" min="0.1" class="input input-bordered w-full @error('current_quantity') input-error @enderror">
                    @error('current_quantity') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                </div>
                
                <button type="button" wire:click="addToCart" class="btn btn-secondary w-full md:w-auto">
                    Añadir al Pedido
                    <span wire:loading wire:target="addToCart" class="loading loading-spinner loading-xs"></span>
                </button>
            </div>

            {{-- Tabla del Carrito --}}
            <h4 class="text-lg font-semibold mb-2">Resumen del Pedido</h4>
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead>
                        <tr>
                            <th>Servicio</th>
                            <th>Cantidad</th>
                            <th>Precio Unit.</th>
                            <th>Subtotal</th>
                            <th class="text-right">Quitar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($cart as $id => $item)
                            <tr wire:key="cart-{{ $id }}">
                                <td>{{ $item['name'] }}</td>
                                <td>{{ $item['quantity'] }} ({{ $item['price_type'] }})</td>
                                <td>S/ {{ number_format($item['price'], 2) }}</td>
                                <td>S/ {{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                                <td class="text-right">
                                    <button type="button" wire:click="removeFromCart({{ $id }})" class="btn btn-ghost btn-xs text-red-500">
                                        &times;
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-gray-500">
                                    Tu pedido está vacío.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    {{-- Fila del Total --}}
                    @if (!empty($cart))
                        <tfoot>
                            <tr class="font-bold text-lg">
                                <td colspan="3" class="text-right">Total:</td>
                                <td colspan="2">S/ {{ number_format($total, 2) }}</td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>

        {{-- PASO 3: CONFIRMAR --}}
        <div class="flex justify-end items-center">
            <div class="text-right mr-6">
                <span class="text-sm text-gray-500">Total del Pedido</span>
                <div class="text-3xl font-bold">S/ {{ number_format($total, 2) }}</div>
            </div>
            <button 
                type="submit" 
                class="btn btn-primary btn-lg"
                @if (empty($cart)) disabled @endif>
                Confirmar Pedido
                <span wire:loading wire:target="save" class="loading loading-spinner loading-xs"></span>
            </button>
        </div>

    </form>
</div>