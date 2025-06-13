<?php

use Livewire\Volt\Volt;
use App\Http\Controllers\PDFMaker;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VerifyReceipt;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CounterController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\FielSello;

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

    Route::get('/user', [ClientController::class, 'final'])->name('client.final');

    #SELLOS Y FIELES
    Route::get('/sello/fecha', [FielSello::class, 'indexsello'])->name('sello.indexsello');
    Route::get('/fiel/fecha', [FielSello::class, 'indexfiel'])->name('fiel.indexfiel');

    Route::get('/receipt/index', [ReceiptController::class, 'index'])->name('receipt.index');
    Route::get('/receipt/create', [ReceiptController::class, 'create'])->name('receipt.create');
    Route::post('/receipt/store', [ReceiptController::class, 'store'])->name('receipt.store');
    Route::get('/receipt/{identificator}', [ReceiptController::class, 'show'])->name('receipt.show');
    Route::get('/receipt/edit/{receipt}', [ReceiptController::class, 'edit'])->name('receipt.edit');
    Route::put('/receipt/update/{identificator}/', [ReceiptController::class, 'update'])->name('receipt.update');
    Route::delete('/receipt/destroy/{receipt}', [ReceiptController::class, 'destroy'])->name('receipt.destroy');
    
    Route::get('/receipt/verify/{identificator}',[VerifyReceipt::class,'__invoke'])->name('receipt.verify');

    Route::get('/sendPDF/{id}',[PDFMaker::class,'sendPDF'])->name('sendPDF');
    Route::get('/downloadPDF/{id}',[PDFMaker::class,'downloadPDF'])->name('downloadPDF');
});

 #Administrar PDF que se pueden subir
        Route::delete('/file/destroy/{document}', [FileController::class, 'destroy'])->name('file.destroy');
        Route::post('/file/{client}', [FileController::class, 'store'])->name('file.store');
        Route::get('/file/download/{document}', [FileController::class, 'download'])->name('file.download');

require __DIR__.'/auth.php';
