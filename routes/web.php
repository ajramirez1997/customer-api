<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('customers');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::get('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

Route::post('/customer-list', [App\Http\Controllers\CustomerListController::class, 'fetchCustomerList'])->name('customer-list');
Route::post('/save-customer', [App\Http\Controllers\CustomerListController::class, 'AddCustomer'])->name('save-customer');
Route::post('/view-customer', [App\Http\Controllers\CustomerListController::class, 'ViewCustomer'])->name('view-customer');
Route::post('/update-customer', [App\Http\Controllers\CustomerListController::class, 'UpdateCustomer'])->name('update-customer');
Route::post('/delete-customer', [App\Http\Controllers\CustomerListController::class, 'DeleteCustomer'])->name('delete-customer');

require __DIR__.'/auth.php';
