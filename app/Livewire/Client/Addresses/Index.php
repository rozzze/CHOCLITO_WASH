<?php

namespace App\Livewire\Client\Addresses;

use App\Models\Address;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;

#[Title('Mis Direcciones')]
class Index extends Component
{
    use WithPagination;

    // ⚠️ IMPORTANTE: Define el tema de paginación para DaisyUI
    protected $paginationTheme = 'tailwind';

    public ?int $deletingId = null;

    /**
     * Prepara el ID de la dirección a eliminar y abre el modal
     */
    public function confirmDelete(int $id): void
    {
        // Solo por seguridad, nos aseguramos que la dirección sea del usuario
        $address = auth()->user()->addresses()->find($id);
        
        if ($address) {
            $this->deletingId = $id;
            $this->dispatch('open-modal', id: 'deleteAddressModal');
        }
    }

    /**
     * Elimina la dirección confirmada
     */
    public function delete(): void
    {
        if (!$this->deletingId) {
            return;
        }

        // Doble verificación de seguridad
        $address = auth()->user()->addresses()->find($this->deletingId);
        
        if ($address) {
            $address->delete();
            session()->flash('success', '¡Dirección eliminada con éxito!');
        } else {
            session()->flash('error', 'No se pudo encontrar la dirección.');
        }

        // Cierra el modal
        $this->dispatch('close-modal', id: 'deleteAddressModal');
        
        // Limpia el ID
        $this->deletingId = null;

        // Resetea la paginación
        $this->resetPage();
    }

    /**
     * Renderiza la vista con las direcciones del usuario actual
     */
    public function render()
    {
        // ¡LA CLAVE! Solo trae las direcciones del usuario logueado
        $addresses = auth()->user()->addresses()->latest()->paginate(5);
        
        return view('livewire.client.addresses.index', [
            'addresses' => $addresses
        ]);
    }
}