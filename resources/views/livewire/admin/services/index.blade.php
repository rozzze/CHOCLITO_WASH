<div class="p-6">
    <h2 class="text-2xl font-semibold mb-6">
        Administrar Servicios de Choclito Wash
    </h2>

    {{-- 1. MENSAJE DE ÉXITO --}}
    @if (session()->has('success'))
        <div role="alert" class="alert alert-success mb-4 shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- MENSAJE DE ERROR --}}
    @if (session()->has('error'))
        <div role="alert" class="alert alert-error mb-4 shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- 2. BOTÓN CREATE --}}
    <div class="flex justify-end mb-4">
        <a 
            href="{{ route('admin.services.create') }}" 
            wire:navigate
            class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Crear Nuevo Servicio
        </a>
    </div>

    {{-- 3. TABLA DE SERVICIOS --}}
    <div class="overflow-x-auto bg-base-100 rounded-box shadow">
        <table class="table w-full">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Tipo de Precio</th>
                    <th>Precio</th>
                    <th>Estado</th>
                    <th class="text-right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($services as $service)
                    <tr wire:key="service-{{ $service->id }}" class="hover">
                        <th class="font-medium">{{ $service->id }}</th>
                        <td>{{ $service->name }}</td>
                        <td>{{ ucfirst($service->price_type) }}</td>
                        <td>S/ {{ number_format($service->price, 2) }}</td>
                        <td>
                            @if ($service->is_active)
                                <span class="badge badge-success badge-outline">Activo</span>
                            @else
                                <span class="badge badge-error badge-outline">Inactivo</span>
                            @endif
                        </td>
                        <td class="text-right">
                            <div class="flex gap-2 justify-end">
                                <a 
                                    href="{{ route('admin.services.edit', $service->id) }}"
                                    wire:navigate
                                    class="btn btn-ghost btn-sm text-blue-500">
                                    Editar
                                </a>
                                <button 
                                    type="button"
                                    wire:click="confirmDelete({{ $service->id }})"
                                    class="btn btn-ghost btn-sm text-red-500"
                                    wire:loading.attr="disabled"
                                    wire:target="confirmDelete({{ $service->id }})">
                                    Borrar
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-8 text-gray-500">
                            No hay servicios registrados todavía.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    {{-- 4. PAGINACIÓN --}}
    <div class="mt-4">
        {{ $services->links() }}
    </div>

    {{-- 5. MODAL DE CONFIRMACIÓN --}}
    <dialog id="deleteConfirmModal" class="modal" wire:ignore>
        <div class="modal-box">
            <h3 class="font-bold text-lg">Confirmación de borrado</h3>
            <p class="py-4">¿Estás seguro de que deseas eliminar este servicio? Esta acción no se puede deshacer.</p>
            <div class="modal-action">
                <button 
                    type="button" 
                    wire:click="delete" 
                    class="btn btn-error"
                    wire:loading.attr="disabled"
                    wire:target="delete">
                    <span wire:loading.remove wire:target="delete">Sí, eliminar</span>
                    <span wire:loading wire:target="delete" class="loading loading-spinner loading-xs"></span>
                    <span wire:loading wire:target="delete">Eliminando...</span>
                </button>
                <form method="dialog">
                    <button class="btn btn-ghost">Cancelar</button>
                </form>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>cerrar</button>
        </form>
    </dialog>

    {{-- 6. SCRIPT PARA EL MODAL --}}
    @script
    <script>
        // Escucha eventos de Livewire
        $wire.on('open-modal', (event) => {
            const modalId = event.id || event[0]?.id;
            if (modalId === 'deleteConfirmModal') {
                const modal = document.getElementById('deleteConfirmModal');
                if (modal) {
                    modal.showModal();
                }
            }
        });

        $wire.on('close-modal', (event) => {
            const modalId = event.id || event[0]?.id;
            if (modalId === 'deleteConfirmModal') {
                const modal = document.getElementById('deleteConfirmModal');
                if (modal) {
                    modal.close();
                }
            }
        });
    </script>
    @endscript
</div>