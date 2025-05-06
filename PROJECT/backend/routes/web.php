<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CustomerController;
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
    return redirect()->route('customers.index');
});

Auth::routes();

Route::get('/home', function () {
    return redirect()->route('customers.index');
})->name('home');

Route::middleware(['auth'])->group(function () {
    Route::resource('customers', CustomerController::class);
});

// Report/Olah Data
Route::get('report/top-products', [ReportController::class, 'topProducts'])->name('report.topProducts');
Route::get('report/top-customers', [ReportController::class, 'topCustomers'])->name('report.topCustomers');
Route::get('report/top-agents', [ReportController::class, 'topAgents'])->name('report.topAgents');
Route::get('report/orders', [ReportController::class, 'orders'])->name('report.orders');
Route::get('report/orders/{id}', [ReportController::class, 'orderDetail'])->name('report.orderDetail');
