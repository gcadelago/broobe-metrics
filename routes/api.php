<?php

use App\Http\Controllers\MetricHistoryRunController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/list-history-metrics', [MetricHistoryRunController::class, 'metricHistory'])->name('list-history-metrics');
Route::post('/get-metrics', [MetricHistoryRunController::class, 'getMetrics'])->name('get-metrics');
