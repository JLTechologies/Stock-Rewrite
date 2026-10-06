<?php

use App\Http\Controllers\StockLabelController;
use App\Http\Controllers\StockQrController;
use Illuminate\Support\Facades\Route;

// The employee panel (Filament) is served at the site root; see AppPanelProvider.

Route::get('/q/{item}', StockQrController::class)->name('stock.qr');

Route::middleware('auth')->group(function (): void {
    Route::get('/stock/labels', StockLabelController::class)->name('stock.labels');
});
