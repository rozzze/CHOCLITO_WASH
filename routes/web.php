<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

// --- TUS RUTAS PÚBLICAS Y DE USUARIO  ---

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');

    
    // --- MÓDULO DE DIRECCIONES DEL CLIENTE ---
    Route::prefix('mis-direcciones')
        ->name('client.addresses.')
        ->group(function () {
            Route::get('/', \App\Livewire\Client\Addresses\Index::class)->name('index');
            Route::get('/nueva', \App\Livewire\Client\Addresses\Create::class)->name('create');
            Route::get('/{address}/editar', \App\Livewire\Client\Addresses\Edit::class)->name('edit');
        });

    // --- MÓDULO DE PEDIDOS DEL CLIENTE ---
    Route::prefix('pedidos')
        ->name('client.orders.')
        ->group(function () {
            Route::get('/', \App\Livewire\Client\Orders\Index::class)->name('index');
            Route::get('/nuevo', \App\Livewire\Client\Orders\Create::class)->name('create');
            Route::get('/{order}', \App\Livewire\Client\Orders\Show::class)->name('show');
        });

    /*

    Rutas del Repartidor

    */
    Route::middleware('role:repartidor')
        ->prefix('mis-tareas')
        ->name('repartidor.')
        ->group(function () {
            Route::get('/', \App\Livewire\Repartidor\Dashboard::class)->name('dashboard');
        });

}); 



/*
Rutas de Administración
*/
Route::middleware(['auth']) 
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        require __DIR__.'/admin.php';
    });

