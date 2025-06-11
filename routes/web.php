<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CounterController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');

    
});

Route::middleware(['auth'])->group(function () {

    Route::get('/counter/index', [CounterController::class, 'index'])->name('counter.index');
    Route::get('/counter/create', [CounterController::class, 'create'])->name('counter.create');
    Route::post('/counter/store', [CounterController::class, 'store'])->name('counter.store');
    Route::get('/counter/{counter}', [CounterController::class, 'show'])->name('counter.show');
    Route::get('/counter/edit/{counter}', [CounterController::class, 'edit'])->name('counter.edit');
    Route::put('/counter/update/{counter}/', [CounterController::class, 'update'])->name('counter.update');
    Route::delete('/counter/destroy/{counter}', [CounterController::class, 'destroy'])->name('counter.destroy'); 

    Route::get('/client/index', [ClientController::class, 'index'])->name('client.index');
    Route::get('/client/create', [ClientController::class, 'create'])->name('client.create');
    Route::post('/client/store', [ClientController::class, 'store'])->name('client.store');
    Route::get('/client/{client}', [ClientController::class, 'show'])->name('client.show');
    Route::get('/client/edit/{client}', [ClientController::class, 'edit'])->name('client.edit');
    Route::put('/client/update/{client}/', [ClientController::class, 'update'])->name('client.update');
    Route::delete('/client/destroy/{client}', [ClientController::class, 'destroy'])->name('client.destroy');
});

require __DIR__.'/auth.php';
