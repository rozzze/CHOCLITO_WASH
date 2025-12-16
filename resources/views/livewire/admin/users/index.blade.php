<div class="p-6">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Gestión de Usuarios</h1>
            <p class="text-gray-500 dark:text-gray-400">Administra los usuarios y sus roles.</p>
        </div>
        <button wire:click="create" class="px-4 py-2 bg-black text-white dark:bg-white dark:text-black font-semibold rounded-lg hover:opacity-80 transition cursor-pointer">
            Nuevo Usuario
        </button>
    </div>

    {{-- Search --}}
    <div class="mb-6">
        <div class="relative max-w-sm">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input wire:model.live="search" type="text" placeholder="Buscar usuario..." class="pl-10 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-white dark:bg-zinc-900 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5 border" />
        </div>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-zinc-800">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nombre</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Email</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Rol</th>
                    <th scope="col" class="relative px-6 py-3">
                        <span class="sr-only">Acciones</span>
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-zinc-900 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($users as $user)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @foreach($user->roles as $role)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                    {{ ucfirst($role->name) }}
                                </span>
                            @endforeach
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button wire:click="edit({{ $user->id }})" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 mr-4 cursor-pointer">Editar</button>
                            @if(auth()->id() !== $user->id)
                                <button wire:click="delete({{ $user->id }})" wire:confirm="¿Estás seguro de eliminar este usuario?" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 cursor-pointer">Eliminar</button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                            No se encontraron usuarios.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>

{{-- Modal Overlay --}}
    @if($showModal)
        <div class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                
                {{-- Backdrop con efecto Blur suave --}}
                <div 
                    class="fixed inset-0 bg-gray-900/75 dark:bg-black/80 backdrop-blur-sm transition-opacity" 
                    aria-hidden="true" 
                    wire:click="$set('showModal', false)">
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                {{-- Modal Panel --}}
                <div class="relative inline-block align-bottom bg-white dark:bg-zinc-900 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-gray-100 dark:border-zinc-700">
                    
                    {{-- Encabezado y Cuerpo --}}
                    <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            
                            {{-- Ícono Decorativo --}}
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 dark:bg-indigo-900/30 sm:mx-0 sm:h-10 sm:w-10 mb-4 sm:mb-0">
                                <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>

                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-xl leading-6 font-bold text-gray-900 dark:text-white" id="modal-title">
                                    {{ $isEdit ? 'Editar Usuario' : 'Crear Nuevo Usuario' }}
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    Complete la información necesaria para registrar al usuario en el sistema.
                                </p>

                                <div class="mt-6 space-y-5">
                                    
                                    {{-- Fila 1: Nombre y Email (Grid) --}}
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        {{-- Name --}}
                                        <div>
                                            <label for="name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Nombre</label>
                                            <input type="text" wire:model="name" id="name" placeholder="Ej: Juan Pérez" 
                                                class="block w-full rounded-lg border-gray-300 dark:border-zinc-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-gray-50 dark:bg-zinc-800 text-gray-900 dark:text-white p-2.5 border transition-colors">
                                            @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                        </div>

                                        {{-- Email --}}
                                        <div>
                                            <label for="email" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Email</label>
                                            <input type="email" wire:model="email" id="email" placeholder="correo@ejemplo.com"
                                                class="block w-full rounded-lg border-gray-300 dark:border-zinc-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-gray-50 dark:bg-zinc-800 text-gray-900 dark:text-white p-2.5 border transition-colors">
                                            @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    {{-- Password --}}
                                    <div>
                                        <label for="password" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                                            Contraseña
                                            @if($isEdit) <span class="text-gray-400 text-xs font-normal ml-1">(Dejar vacío para mantener actual)</span> @endif
                                        </label>
                                        <input type="password" wire:model="password" id="password" placeholder="••••••••"
                                            class="block w-full rounded-lg border-gray-300 dark:border-zinc-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-gray-50 dark:bg-zinc-800 text-gray-900 dark:text-white p-2.5 border transition-colors">
                                        @error('password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>

                                    {{-- Role --}}
                                    <div>
                                        <label for="role" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Rol Asignado</label>
                                        <div class="relative">
                                            <select wire:model="selectedRole" id="role" 
                                                class="block w-full rounded-lg border-gray-300 dark:border-zinc-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-gray-50 dark:bg-zinc-800 text-gray-900 dark:text-white p-2.5 border appearance-none transition-colors">
                                                <option value="">Seleccione un rol...</option>
                                                @foreach($roles as $role)
                                                    <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                                                @endforeach
                                            </select>
                                            {{-- Custom Arrow Icon --}}
                                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700 dark:text-gray-300">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                            </div>
                                        </div>
                                        @error('selectedRole') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Footer de Botones --}}
                    <div class="bg-gray-50 dark:bg-zinc-800/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100 dark:border-zinc-700">
                        <button wire:click="save" type="button" class="w-full inline-flex justify-center items-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm transition-all cursor-pointer">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Guardar Cambios
                        </button>
                        <button wire:click="$set('showModal', false)" type="button" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-zinc-800 text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-zinc-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-all cursor-pointer">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
