<?php

use App\Http\Controllers\Auth\AdminController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerGroupController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->group(function () {
    Route::resource('customer', CustomerController::class);
    Route::view('/customer-page', 'pages.customer.customer')->name('customer-page');
    Route::resource('customer-groups', CustomerGroupController::class);
    Route::view('/customer-group-page', 'pages.customergroup.customer_group')->name('customer-group-page');
    // Route::controller(CustomerGroupController::class)->group(function () {
    //     Route::get('/customer-group', 'index')->name('customer-group');
    //     // Route::post('customer', 'store');
    // });
});

Route::get('logout', [AdminController::class, 'logout'])->name('logout');
Route::view('change-password', 'admin.change_password')->name('change-password');
Route::post('change-password', [AdminController::class, 'changePassword']);
Route::redirect('/', 'login');

Route::get('get-token', [AdminController::class,'testa']);

Route::get('login', function () {
    return view('admin.login');
});

Route::post('login', [AdminController::class, 'login'])->name('login');
Route::view('forgot-password', 'admin.forgot_password')->name('forgot-password');
Route::post('send-reset-mail', [AdminController::class, 'resetMail'])->name('send-reset-mail');
Route::get('enter-password', [AdminController::class,'enterPassword'])->name('reset.password.enter');
Route::post('enter-password', [AdminController::class,'store'])->name('reset.password.enter');