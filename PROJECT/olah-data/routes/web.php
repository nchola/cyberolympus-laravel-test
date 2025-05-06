<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;

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

Route::get('/', function () {
    return redirect()->route('report.orders');
});

// Report Routes
Route::prefix('report')->name('report.')->group(function () {
    Route::get('top-products', [ReportController::class, 'topProducts'])->name('topProducts');
    Route::get('top-customers', [ReportController::class, 'topCustomers'])->name('topCustomers');
    Route::get('top-agents', [ReportController::class, 'topAgents'])->name('topAgents');
    Route::get('orders', [ReportController::class, 'orders'])->name('orders');
    Route::get('orders/{id}', [ReportController::class, 'orderDetail'])->name('orderDetail');
});
