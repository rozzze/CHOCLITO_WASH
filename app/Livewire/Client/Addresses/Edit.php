<?php

namespace App\Livewire\Client\Addresses;

use App\Models\Address;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Rule;

#[Title('Editar Dirección')]
class Edit extends Component
{
    public Address $address;

    #[Rule('required|string|max:100', message: 'El alias es obligatorio.')]
    public $alias = '';

    #[Rule('required|string|max:255', message: 'La dirección es obligatoria.')]
    public $street = '';

    #[Rule('required|string|max:100', message: 'El distrito es obligatorio.')]
    public $district = '';

    #[Rule('nullable|string|max:255', message: 'La referencia es muy larga.')]
    public $reference = '';

    /**
     * Carga el componente y VERIFICA LA PROPIEDAD
     */
    public function mount(Address $address)
    {
        // ¡¡LA MAYOR SEGURIDAD ESTÁ AQUÍ!!
        // Comprueba que la dirección que se intenta editar pertenece al usuario logueado.
        if ($address->user_id !== auth()->id()) {
            abort(403); // Prohibido
        }

        $this->address = $address;
        
        // Llena el formulario con los datos existentes
        $this->alias = $address->alias;
        $this->street = $address->street;
        $this->district = $address->district;
        $this->reference = $address->reference;
    }

    /**
     * Actualiza la dirección
     */
    public function update()
    {
        $validated = $this->validate();

        $this->address->update($validated);

        session()->flash('success', '¡Dirección actualizada con éxito!');

        $this->redirect(route('client.addresses.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.client.addresses.edit');
    }
}