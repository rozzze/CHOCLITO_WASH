<?php

namespace App\Livewire\Admin\Services;

use App\Models\Service;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;

#[Title('Gestión de Servicios')]
class Index extends Component
{
    use WithPagination;

    // ⚠️ IMPORTANTE: Define el tema de paginación para DaisyUI
    protected $paginationTheme = 'tailwind';

    public ?int $deletingId = null;

    /**
     * Prepara el ID del servicio a eliminar y abre el modal
     */
    public function confirmDelete(int $id): void
    {
        $this->deletingId = $id;
        $this->dispatch('open-modal', id: 'deleteConfirmModal');
    }

    /**
     * Elimina el servicio confirmado
     */
    public function delete(): void
    {
        if (!$this->deletingId) {
            return;
        }

        $service = Service::find($this->deletingId);
        
        if ($service) {
            $service->delete();
            session()->flash('success', '¡Servicio eliminado con éxito!');
        } else {
            session()->flash('error', 'El servicio no existe.');
        }

        // Cierra el modal
        $this->dispatch('close-modal', id: 'deleteConfirmModal');
        
        // Limpia el ID
        $this->deletingId = null;

        // Resetea la paginación si estamos en una página vacía
        $this->resetPage();
    }

    /**
     * Renderiza la vista con los servicios paginados
     */
    public function render()
    {
        return view('livewire.admin.services.index', [
            'services' => Service::latest()->paginate(10)
        ]);
    }
}
