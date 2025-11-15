<?php

namespace App\Livewire\Admin\Services; // <- Coincide con tu namespace

use App\Models\Service;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Editar Servicio')]
class Edit extends Component // <- Nombre de clase 'Edit'
{
    public $name = '';
    public $description = '';
    public $price_type = 'por_kg';
    public $price = '';
    public $is_active = true;

    public Service $service;

    public function rules()
    {
        return [
            'name' => 'required|string|min:3|max:255|unique:services,name,' . $this->service->id,
            'description' => 'nullable|string',
            'price_type' => 'required|in:por_kg,por_unidad',
            'price' => 'required|numeric|min:0.01',
            'is_active' => 'boolean',
        ];
    }
    
    protected $messages = [
        'name.required' => 'El nombre es obligatorio.',
        'name.unique' => 'Este nombre de servicio ya existe.',
        'price_type.required' => 'Selecciona un tipo de precio.',
        'price.required' => 'El precio es obligatorio.',
        'price.numeric' => 'El precio debe ser un número válido.',
    ];

    public function mount(Service $service)
    {
        $this->service = $service;
        $this->name = $service->name;
        $this->description = $service->description;
        $this->price_type = $service->price_type;
        $this->price = $service->price;
        $this->is_active = $service->is_active;
    }

    public function update()
    {
        $validated = $this->validate($this->rules());
        $this->service->update($validated);
        session()->flash('success', '¡Servicio actualizado con éxito!');
        $this->redirect(route('admin.services.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.services.edit'); // <- Apunta a la vista 'edit'
    }
}