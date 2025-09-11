<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExpeditionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Redirect root to expeditions index
Route::get('/', function () {
    return redirect()->route('expeditions.index');
});

// Expedition routes
Route::resource('expeditions', ExpeditionController::class);

// Additional expedition routes
Route::get('/expeditions/export/pdf', [ExpeditionController::class, 'exportPdf'])->name('expeditions.export.pdf');
Route::get('/expeditions/export/excel', [ExpeditionController::class, 'exportExcel'])->name('expeditions.export.excel');
Route::get('/expeditions/{expedition}/export/pdf', [ExpeditionController::class, 'exportSinglePdf'])->name('expeditions.export.single.pdf');
Route::get('/api/expeditions/statistics', [ExpeditionController::class, 'statistics'])->name('expeditions.statistics');
