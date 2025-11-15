<?php

namespace App\Livewire\Admin\Services; // <- Coincide con tu namespace

use App\Models\Service;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Rule;

#[Title('Crear Nuevo Servicio')]
class Create extends Component // <- Nombre de clase 'Create'
{
    #[Rule('required|string|min:3|max:255|unique:services,name', message: 'El nombre es obligatorio y debe ser único.')]
    public $name = '';

    #[Rule('nullable|string', message: 'La descripción es muy larga.')]
    public $description = '';

    #[Rule('required|in:por_kg,por_unidad', message: 'Selecciona un tipo de precio.')]
    public $price_type = 'por_kg';

    #[Rule('required|numeric|min:0.01', message: 'El precio debe ser un número válido.')]
    public $price = '';

    #[Rule('boolean')]
    public $is_active = true;
    
    public function save()
    {
        $validated = $this->validate();
        Service::create($validated);
        session()->flash('success', '¡Servicio creado con éxito!');
        $this->redirect(route('admin.services.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.services.create'); // <- Apunta a la vista 'create'
    }
}