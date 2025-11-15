<div class="p-6">
    <h2 class="text-2xl font-semibold mb-6">
        Editar Servicio: <span class="text-primary">{{ $name }}</span>
    </h2>

    <form wire:submit.prevent="update" class="p-6 bg-base-100 rounded-box shadow-lg">
        <div class="grid gap-4 grid-cols-2">
            
            {{-- Nombre del Servicio --}}
            <div class="form-control col-span-2">
                <label for="name" class="label">
                    <span class="label-text">Nombre del Servicio</span>
                </label>
                <input 
                    type="text" 
                    id="name"
                    wire:model="name"
                    class="input input-bordered w-full @error('name') input-error @enderror" 
                    placeholder="Ej: Lavado al Peso">
                @error('name') 
                    <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div>
                @enderror
            </div>

            {{-- Tipo de Precio --}}
            <div class="form-control col-span-1">
                <label for="price_type" class="label">
                    <span class="label-text">Tipo de Precio</span>
                </label>
                <select 
                    id="price_type" 
                    wire:model="price_type"
                    class="select select-bordered w-full @error('price_type') select-error @enderror">
                    <option value="por_kg">Por Kilogramo (Kg)</option>
                    <option value="por_unidad">Por Unidad</option>
                </select>
                @error('price_type') 
                    <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div>
                @enderror
            </div>

            {{-- Precio --}}
            <div class="form-control col-span-1">
                <label for="price" class="label">
                    <span class="label-text">Precio (S/.)</span>
                </label>
                <input 
                    type="number" 
                    id="price"
                    wire:model="price"
                    step="0.01"
                    class="input input-bordered w-full @error('price') input-error @enderror" 
                    placeholder="Ej: 5.00">
                @error('price') 
                    <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div>
                @enderror
            </div>

            {{-- Descripción --}}
            <div class="form-control col-span-2">
                <label for="description" class="label">
                    <span class="label-text">Descripción (Opcional)</span>
                </label>
                <textarea 
                    id="description" 
                    wire:model="description"
                    rows="3" 
                    class="textarea textarea-bordered w-full @error('description') textarea-error @enderror" 
                    placeholder="Escribe una breve descripción..."></textarea>
                @error('description') 
                    <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div>
                @enderror
            </div>

            {{-- Checkbox de Activo --}}
            <div class="form-control col-span-2">
                <label for="is_active" class="label cursor-pointer justify-start gap-3">
                     <input 
                        id="is_active" 
                        wire:model="is_active"
                        type="checkbox" 
                        class="checkbox checkbox-primary">
                    <span class="label-text">¿Servicio Activo?</span> 
                </label>
            </div>
        </div>

        {{-- Botones de Acción --}}
        <div class="flex items-center space-x-4 mt-6">
            <button 
                type="submit" 
                class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"></path></svg>
                Actualizar Servicio
                <span wire:loading wire:target="update" class="loading loading-spinner loading-xs"></span>
            </button>
            <a 
                href="{{ route('admin.services.index') }}" 
                wire:navigate
                class="btn btn-ghost">
                Cancelar
            </a>
        </div>
    </form>
</div>