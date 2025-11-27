<?php

use App\Http\Controllers\AmytStockController;
use App\Http\Controllers\AttributeController;
use App\Http\Controllers\Auth\AdminController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerGroupController;
use App\Http\Controllers\CustomerStockController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\YarnController;
use App\Http\Controllers\YarnStockController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware('AuthCheck')->group(function () {
    Route::get('dashboard',  [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('customer', CustomerController::class);
    Route::view('customer-list', 'pages.customer.customer')->name('customer-list');
    Route::resource('customer-groups', CustomerGroupController::class);
    Route::view('customer-group-list', 'pages.customergroup.customer_group')->name('customer-group-list');
    Route::resource('expense/category', ExpenseCategoryController::class);
    Route::view('expense/category-list', 'pages.expense.expense_category')->name('expense/category-list');
    Route::get('expense/by-date', [ExpenseController::class, 'getByDate'])->name('expense.by-date');
    Route::resource('expense', ExpenseController::class);
    Route::view('expense-list', 'pages.expense.expense')->name('expense-list');
    Route::resource('supplier', SupplierController::class);
    Route::view('supplier-list', 'pages.supplier.supplier')->name('supplier-list');
    Route::resource('purchase', PurchaseController::class);
    Route::post('purchase/{id}/approve', [PurchaseController::class, 'purchaseStatus']);
    Route::get('/view-purchase-details/{id}', [PurchaseController::class, 'showDetails']);
    // Route::view('create-purchase', 'pages.purchase.create_purchase')->name('create-purchase');
    Route::view('purchase-list', 'pages.purchase.purchase')->name('purchase-list');
    Route::resource('service', ServiceController::class);
    Route::view('challan-list', 'pages.challan.challan')->name('service-list');
    Route::view('invoice-list', 'pages.invoice.invoice')->name('invoice-list');
    Route::view('quotation-list', 'pages.challan.quotaion')->name('quotation-list');
    Route::get('/view-challan-details/{id}', [ServiceController::class, 'showDetails']);
    Route::get('service/{id}/approve', [ServiceController::class, 'serviceStatus'])->name('service-approve');
    Route::resource('yarn-count', YarnController::class); // Restoring original resource route
    Route::get('all-yarn-counts', [YarnController::class, 'allYarns'])->name('all-yarn-counts'); // Route for fetching all yarns
    Route::view('yarn-count-list', 'pages.yarn.yarn_list')->name('yarn-count-list');
    // amyt-stock-list
    Route::view('amyt-stock-list', 'pages.stock.amyt_stock')->name('amyt-stock-list');
    //stock-list
    Route::view('customer-stock-list', 'pages.stock.customer_stock')->name('customer-stock-list');
    Route::get('stock-list', [AmytStockController::class, 'stockList'])->name('stock-list');
    Route::post('purchase-to-stock/{id}', [AmytStockController::class, 'purchaseToStock'])->name('purchase-to-stock');
    // Route::view('customer-stock-page', 'pages.stock.create_stock')->name('customer-stock-page');
    Route::get('/customer-stock-page', [CustomerStockController::class, 'create']);
    Route::post('customer-stock-in', [CustomerStockController::class, 'stockIn'])->name('customer-stock-in');
    Route::post('customer-item-to-stock/{id}', [CustomerStockController::class, 'loadTostockIn'])->name('customer-item-to-stock');
    Route::delete('customer-stock/{id}', [CustomerStockController::class, 'destroyChallan']);
    // Route for Service creation
    Route::view('create-challan', 'pages.challan.create_challan')->name('create-service');
    Route::view('attribute-list', 'pages.attribute.attribute')->name('attribute-list');
    Route::view('show-stock-report', 'pages.report.total_current_stock')->name('show-stock.report');
    Route::get('amyt-customer-stock-list', [ReportController::class, 'totalStockList'])->name('amyt-customer-stock-list');
    Route::get('customer-individual', [YarnStockController::class, 'showStockStatement'])->name('amyt-customer-stock-list');
    Route::resource('attribute', AttributeController::class);

    Route::get('create-purchase', [PurchaseController::class, 'create'])->name('create-purchase');
    Route::post('/purchase', [PurchaseController::class, 'store'])->name('purchase.store');
    Route::get('/supplier', [SupplierController::class, 'search'])->name('supplier.search');
});

Route::get('logout', [AdminController::class, 'logout'])->name('logout');
Route::view('change-password', 'admin.change_password')->name('change-password');
Route::post('change-password', [AdminController::class, 'changePassword']);
Route::redirect('/', 'login');

Route::get('get-token', [AdminController::class, 'testa']);

Route::get('login', function () {
    return view('admin.login');
});

Route::post('login', [AdminController::class, 'login'])->name('login');
Route::view('forgot-password', 'admin.forgot_password')->name('forgot-password');
Route::post('send-reset-mail', [AdminController::class, 'resetMail'])->name('send-reset-mail');
Route::get('enter-password', [AdminController::class, 'enterPassword'])->name('reset.password.enter');
Route::post('enter-password', [AdminController::class, 'store'])->name('reset.password.enter');
// view invoice
