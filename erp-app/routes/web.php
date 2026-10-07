<?php

use App\Http\Controllers\IncidentPdfController;
use App\Http\Controllers\IncidentPhotoController;
use App\Http\Controllers\ItAssetLabelController;
use App\Http\Controllers\ItAssetQrController;
use App\Http\Controllers\StockLabelController;
use App\Http\Controllers\StockQrController;
use App\Http\Controllers\SuggestionPdfController;
use App\Http\Controllers\SuggestionPhotoController;
use Illuminate\Support\Facades\Route;

// The employee panel (Filament) is served at the site root; see AppPanelProvider.

Route::get('/q/{item}', StockQrController::class)->name('stock.qr');
Route::get('/it/{asset}', ItAssetQrController::class)->name('it.qr');

Route::middleware('auth')->group(function (): void {
    Route::get('/stock/labels', StockLabelController::class)->name('stock.labels');
    Route::get('/it-labels', ItAssetLabelController::class)->name('it.labels');
    Route::get('/incidents/{incident}/photos/{index}', IncidentPhotoController::class)->whereNumber('index')->name('incidents.photo');
    Route::get('/incident-pdf', IncidentPdfController::class)->name('incidents.pdf');
    Route::get('/idea-box/{suggestion}/photos/{index}', SuggestionPhotoController::class)->whereNumber('index')->name('suggestions.photo');
    Route::get('/idea-box-pdf', SuggestionPdfController::class)->name('suggestions.pdf');
});
