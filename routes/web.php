<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChartController;

Route::get('/', [ChartController::class, 'index'])->name('home');
Route::get('/charts', [ChartController::class, 'index'])->name('charts.index');
Route::get('/charts/template/{type}', [ChartController::class, 'downloadTemplate'])->name('charts.template');
Route::post('/charts/upload', [ChartController::class, 'upload'])->name('charts.upload');
Route::get('/charts/{chart}', [ChartController::class, 'show'])->name('charts.show');
Route::get('/charts/{chart}/edit', [ChartController::class, 'edit'])->name('charts.edit');
Route::put('/charts/{chart}', [ChartController::class, 'update'])->name('charts.update');
Route::delete('/charts/{chart}', [ChartController::class, 'destroy'])->name('charts.destroy');
Route::get('/charts/{chart}/download', [ChartController::class, 'downloadChartData'])->name('charts.download');

// Route::get('/', function () {
//     return view('welcome');
// });
