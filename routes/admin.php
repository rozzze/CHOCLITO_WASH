<?php

use Illuminate\Support\Facades\Route;

// --- MÓDULO DE SERVICIOS ---
use App\Livewire\Admin\Services\Index as ServiceIndex;
use App\Livewire\Admin\Services\Create as ServiceCreate;
use App\Livewire\Admin\Services\Edit as ServiceEdit;

// ¡CAMBIO 1! Grupo solo para el Admin
Route::middleware('role:admin')->group(function () {
    Route::get('servicios', ServiceIndex::class)->name('services.index');
    Route::get('servicios/crear', ServiceCreate::class)->name('services.create');
    Route::get('servicios/{service}/editar', ServiceEdit::class)->name('services.edit');
});


// --- MÓDULO DE PEDIDOS ---
use App\Livewire\Admin\Orders\Index as OrderIndex;
use App\Livewire\Admin\Orders\Show as OrderShow;

// ¡CAMBIO 2! Grupo para Admin Y Operario
Route::middleware('role:admin|operario')->group(function () {
    Route::get('pedidos', OrderIndex::class)->name('orders.index');
    Route::get('pedidos/{order}', OrderShow::class)->name('orders.show');
});