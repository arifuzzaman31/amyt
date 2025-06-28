<?php

use App\Http\Controllers\AmytStockController;
use App\Http\Controllers\Auth\AdminController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerGroupController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\YarnController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->group(function () {
    Route::view('dashboard','pages.dashboard');
    Route::resource('customer', CustomerController::class);
    Route::view('customer-list', 'pages.customer.customer')->name('customer-list');
    Route::resource('customer-groups', CustomerGroupController::class);
    Route::view('customer-group-list', 'pages.customergroup.customer_group')->name('customer-group-list');
    Route::resource('expense/category', ExpenseCategoryController::class);
    Route::view('expense/category-list', 'pages.expense.expense_category')->name('expense/category-list');
    Route::resource('expense', ExpenseController::class);
    Route::view('expense-list', 'pages.expense.expense')->name('expense-list');
    Route::resource('supplier', SupplierController::class);
    Route::view('supplier-list', 'pages.supplier.supplier')->name('supplier-list');
    Route::resource('purchase', PurchaseController::class);
    Route::post('purchase/{id}/approve', [PurchaseController::class, 'purchaseStatus'])->name('purchase-to-stock');
    Route::view('create-purchase', 'pages.purchase.create_purchase')->name('create-purchase');
    Route::view('purchase-list', 'pages.purchase.purchase')->name('purchase-list');
    Route::resource('service', ServiceController::class);
    Route::view('service-list', 'pages.service.service')->name('service-list');
    Route::resource('yarn-count', YarnController::class); // Restoring original resource route
    Route::get('all-yarn-counts', [YarnController::class, 'allYarns'])->name('all-yarn-counts'); // Route for fetching all yarns
    Route::view('yarn-count-list', 'pages.yarn.yarn_list')->name('yarn-count-list');
    // amyt-stock-list
    Route::view('amyt-stock-list', 'pages.stock.amyt_stock')->name('amyt-stock-list');
    Route::view('customer-stock-list', 'pages.stock.customer_stock')->name('customer-stock-list');
    //stock-list
    Route::get('stock-list', [AmytStockController::class, 'stockList'])->name('stock-list');
    Route::post('purchase-to-stock/{id}', [AmytStockController::class, 'purchaseToStock'])->name('purchase-to-stock');
});

// Removing the 'api' prefix group that was added
// Route::prefix('api')->group(function () {
//     Route::resource('yarns', YarnController::class)->except(['create', 'edit']);
//     Route::get('all-yarns', [YarnController::class, 'allYarns']);
// });

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