<?php

use App\Http\Controllers\ExportController;
use Illuminate\Support\Facades\Route;

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

Route::get('', [ExportController::class, 'index'])->name('home');
Route::get('harian', [ExportController::class, 'indexHarian'])->name('home-harian');
Route::get('mingguan', [ExportController::class, 'indexMingguan'])->name('home-mingguan');

Route::post('export', [ExportController::class, 'ExportExcel'])->name('export');
Route::post('mingguan', [ExportController::class, 'ExportExcelMingguan'])->name('export-mingguan');
