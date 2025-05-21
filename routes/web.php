<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SalesOrderController;
use App\Http\Controllers\SalesOrderExportController;
use App\Http\Controllers\SalesOrderDashboardController;
use App\Http\Controllers\SalesOrderUtilityController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\SystemLogController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


// Semua route di bawah hanya bisa diakses jika sudah login
Route::middleware(['auth'])->group(function () {

    // Dashboard (halaman utama)
    Route::get('/', [SalesOrderDashboardController::class, 'dashboard'])->name('dashboard');
Route::get('/system/logs', [SystemLogController::class, 'index'])->name('system.logs');

    // Sales Orders
    Route::prefix('sales-orders')->group(function () {
        Route::get('/', [SalesOrderController::class, 'index'])->name('sales-orders.index');
        Route::get('/{id}', [SalesOrderController::class, 'show'])->name('sales-orders.show')->whereNumber('id');
        Route::put('/{id}', [SalesOrderController::class, 'update'])->name('sales-orders.update')->whereNumber('id');
        Route::delete('/{id}/notes', [SalesOrderController::class, 'clearNotes'])->name('sales-orders.clear-notes')->whereNumber('id');
        Route::get('/export', [SalesOrderExportController::class, 'export'])->name('sales-orders.export');
//    Route::get('/export-sales-orders', [SalesOrderExportController::class, 'export']);
   
    });

    // Utility
    Route::get('/active-customers', [SalesOrderUtilityController::class, 'activeCustomers'])->name('sales-orders.active-customers');
    Route::get('/pending-orders', [SalesOrderUtilityController::class, 'pendingOrders'])->name('sales-orders.pending-orders');
    Route::get('/orders-by-customer/{customer}', [SalesOrderUtilityController::class, 'getOrdersByCustomer'])->name('sales-orders.by-customer');
    Route::get('/sales-orders/{customer}', [SalesOrderController::class, 'getByCustomer']);

Route::get('/customer/{customer}/sales-orders', function ($customer) {
    $salesOrders = DB::table('sales_orders')
        ->where('Customer', $customer)
        ->get();

    return view('customer.sales-orders', [
        'customer' => $customer,
        'salesOrders' => $salesOrders
    ]);
})->name('customer.salesorders');


    // Notifications
    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
    // Route::get('/notifications/delivery-delays/{range}', [NotificationController::class, 'showByDelayRange'])
    //     ->name('notifications.by-range')
    //     ->whereIn('range', ['1-6', '7-14', '14plus']);
   Route::get('/notifikasi/detail', [NotifikasiController::class, 'showNotifikasi'])->name('shownotifikasi');


    // Profile
    Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.update.photo');
    Route::delete('/profile/photo', [ProfileController::class, 'deletePhoto'])->name('profile.photo.delete');

    });



});
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login'); // atau route('login')
})->name('logout');


// Route otentikasi Laravel (login, register, logout, dll)
require __DIR__.'/auth.php';
