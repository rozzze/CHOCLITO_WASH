<?php

namespace App\Livewire\Client\Addresses;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Rule;

#[Title('Agregar Nueva Dirección')]
class Create extends Component
{
    #[Rule('required|string|max:100', message: 'El alias es obligatorio (Ej: Casa, Oficina).')]
    public $alias = '';

    #[Rule('required|string|max:255', message: 'La dirección es obligatoria.')]
    public $street = '';

    #[Rule('required|string|max:100', message: 'El distrito es obligatorio.')]
    public $district = '';

    #[Rule('nullable|string|max:255', message: 'La referencia es muy larga.')]
    public $reference = '';

    /**
     * Guarda la nueva dirección asociada al usuario logueado
     */
    public function save()
    {
        $validated = $this->validate();

        // ¡LA CLAVE! Se asigna automáticamente al usuario logueado
        auth()->user()->addresses()->create($validated);

        session()->flash('success', '¡Dirección guardada con éxito!');

        $this->redirect(route('client.addresses.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.client.addresses.create');
    }
}