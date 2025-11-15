<div class="p-6">
    <h2 class="text-2xl font-semibold mb-6">
        Agregar Nueva Dirección
    </h2>

    <form wire:submit.prevent="save" class="p-6 bg-base-100 rounded-box shadow-lg max-w-2xl">
        <div class="grid gap-4 grid-cols-1">
            
            {{-- Alias --}}
            <div class="form-control w-full">
                <label for="alias" class="label">
                    <span class="label-text">Alias</span>
                </label>
                <input 
                    type="text" 
                    id="alias"
                    wire:model="alias"
                    class="input input-bordered w-full @error('alias') input-error @enderror" 
                    placeholder="Ej: Casa, Oficina">
                @error('alias') 
                    <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div>
                @enderror
            </div>

            {{-- Dirección --}}
            <div class="form-control w-full">
                <label for="street" class="label">
                    <span class="label-text">Dirección</span>
                </label>
                <input 
                    type="text" 
                    id="street"
                    wire:model="street"
                    class="input input-bordered w-full @error('street') input-error @enderror" 
                    placeholder="Ej: Av. Siempre Viva 123">
                @error('street') 
                    <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div>
                @enderror
            </div>

            {{-- Distrito --}}
            <div class="form-control w-full">
                <label for="district" class="label">
                    <span class="label-text">Distrito</span>
                </label>
                <input 
                    type="text" 
                    id="district"
                    wire:model="district"
                    class="input input-bordered w-full @error('district') input-error @enderror" 
                    placeholder="Ej: Yanahuara">
                @error('district') 
                    <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div>
                @enderror
            </div>

            {{-- Referencia --}}
            <div class="form-control w-full">
                <label for="reference" class="label">
                    <span class="label-text">Referencia (Opcional)</span>
                </label>
                <textarea 
                    id="reference" 
                    wire:model="reference"
                    rows="3" 
                    class="textarea textarea-bordered w-full @error('reference') textarea-error @enderror" 
                    placeholder="Ej: Portón negro, tocar intercomunicador 101..."></textarea>
                @error('reference') 
                    <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div>
                @enderror
            </div>
        </div>

        {{-- Botones de Acción --}}
        <div class="flex items-center space-x-4 mt-6">
            <button 
                type="submit" 
                class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" /></svg>
                Guardar Dirección
                <span wire:loading wire:target="save" class="loading loading-spinner loading-xs"></span>
            </button>
            <a 
                href="{{ route('client.addresses.index') }}" 
                wire:navigate
                class="btn btn-ghost">
                Cancelar
            </a>
        </div>
    </form>
</div>