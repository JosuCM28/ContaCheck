<?php

use App\Models\Receipt;
use Livewire\Volt\Volt;
use App\Http\Controllers\PDFMaker;
use App\Http\Controllers\FielSello;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VerifyReceipt;
use App\Http\Controllers\FileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TaskController;
use App\Services\CancelarTimbradoService;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CounterController;

use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataEmisorController;
use App\Http\Controllers\InventorieController;
use App\Http\Controllers\ClientPortalController;

Route::get('/', [HomeController::class, 'index'])->name('home')->middleware('redirect');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');
    
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

Route::middleware(['auth', 'role:contador'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    //DataCompany
    Route::get('emisor', [DataEmisorController::class, 'index'])->name('emisor.index');
    Route::get('emisor/edit', [DataEmisorController::class, 'edit'])->name('emisor.edit');
    Route::put('emisor/update', [DataEmisorController::class, 'update'])->name('emisor.update');
    
    Route::get('counter/index', [CounterController::class, 'index'])->name('counter.index');
    Route::get('counter/create', [CounterController::class, 'create'])->name('counter.create');
    Route::post('counter/store', [CounterController::class, 'store'])->name('counter.store');
    Route::get('counter/{counter}', [CounterController::class, 'show'])->name('counter.show');
    Route::get('counter/edit/{counter}', [CounterController::class, 'edit'])->name('counter.edit');
    Route::put('counter/update/{counter}/', [CounterController::class, 'update'])->name('counter.update');
    Route::delete('counter/destroy/{counter}', [CounterController::class, 'destroy'])->name('counter.destroy'); 

    Route::get('client/index', [ClientController::class, 'index'])->name('client.index');
    Route::get('client/create', [ClientController::class, 'create'])->name('client.create');
    Route::post('client/store', [ClientController::class, 'store'])->name('client.store');
    Route::get('client/{client}', [ClientController::class, 'show'])->name('client.show');
    Route::get('client/edit/{client}', [ClientController::class, 'edit'])->name('client.edit');
    Route::put('client/update/{client}/', [ClientController::class, 'update'])->name('client.update');
    Route::delete('client/destroy/{client}', [ClientController::class, 'destroy'])->name('client.destroy');

    // Route::get('user', [ClientController::class, 'final'])->name('client.final');

    Route::get('sello/fecha', [FielSello::class, 'indexsello'])->name('sello.indexsello');
    Route::get('fiel/fecha', [FielSello::class, 'indexfiel'])->name('fiel.indexfiel');

    Route::get('receipt/index', [ReceiptController::class, 'index'])->name('receipt.index');
    Route::get('receipt/create', [ReceiptController::class, 'create'])->name('receipt.create');
    Route::post('receipt/store', [ReceiptController::class, 'store'])->name('receipt.store');
    Route::get('receipt/{identificator}', [ReceiptController::class, 'show'])->name('receipt.show');
    Route::get('receipt/edit/{receipt}', [ReceiptController::class, 'edit'])->name('receipt.edit');
    Route::put('receipt/update/{receipt}/', [ReceiptController::class, 'update'])->name('receipt.update');
    Route::delete('receipt/destroy/{receipt}', [ReceiptController::class, 'destroy'])->name('receipt.destroy');
    

    Route::get('sendPDF/{id}',[PDFMaker::class,'sendPDF'])->name('sendPDF');

    Route::resource('inventories', InventorieController::class);

    Route::get('cancelar/timbrado/{id}', [CancelarTimbradoService::class, 'cancelarCFDI'])->name('cancelarCFDI');

    Route::get('timbrar/recibo/{id}', [ReceiptController::class, 'timbrarRecibo'])->name('timbrar.recibo');

    
    Route::get('kanban', [TaskController::class, 'index'])->name('kanban.index');
    Route::post('kanban', [TaskController::class, 'store'])->name('kanban.store');
    Route::post('kanban/update-draggable', [TaskController::class, 'updateDraggable'])->name('kanban.update-draggable');
    Route::put('kanban/{task}', [TaskController::class, 'update'])->name('kanban.update');
    Route::delete('kanban/{task}', [TaskController::class, 'destroy'])->name('kanban.destroy');
    
});

Route::get('downloadPDF/{id}',[PDFMaker::class,'downloadPDF'])->name('downloadPDF');

Route::middleware(['auth', 'role:cliente'])->group(function () {
    Route::get('inicio', [ClientPortalController::class, 'index'])->name('client.dashboard');
    Route::get('recibos', [ClientPortalController::class, 'receipts'])->name('client.receipts');
    Route::get('archivos', [ClientPortalController::class, 'files'])->name('client.files');
});

//verificacion pdf
Route::get('receipt/verify/{identificator}',[VerifyReceipt::class,'__invoke'])->name('receipt.verify');

Route::delete('file/destroy/{document}', [FileController::class, 'destroy'])->name('file.destroy');
Route::post('file/{client}', [FileController::class, 'store'])->name('file.store');
Route::get('file/download/{document}', [FileController::class, 'download'])->name('file.download');

Route::get('pdfview', [PDFMaker::class, 'showPDF'])->name('pdfview');

require __DIR__.'/auth.php';
