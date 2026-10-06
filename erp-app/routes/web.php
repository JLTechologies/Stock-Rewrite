<?php

use App\Http\Controllers\ItAssetLabelController;
use App\Http\Controllers\ItAssetQrController;
use App\Http\Controllers\StockLabelController;
use App\Http\Controllers\StockQrController;
use Illuminate\Support\Facades\Route;

// The employee panel (Filament) is served at the site root; see AppPanelProvider.

Route::get('/q/{item}', StockQrController::class)->name('stock.qr');
Route::get('/it/{asset}', ItAssetQrController::class)->name('it.qr');

Route::middleware('auth')->group(function (): void {
    Route::get('/stock/labels', StockLabelController::class)->name('stock.labels');
    Route::get('/it-labels', ItAssetLabelController::class)->name('it.labels');
});
