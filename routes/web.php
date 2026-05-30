<?php

use App\Http\Controllers\Admin\OutletController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\SalaryController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CashierController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Superadmin\TenantController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/api-docs', function () {
    return view('swagger');
});

Route::prefix('cashier')->name('cashier.')->middleware(['auth', 'role:kasir,super-admin'])->group(function () {
    Route::get('/', [CashierController::class, 'index'])->name('index');
    // Kasir butuh akses untuk menyimpan transaksi
    Route::post('/transaction', [TransactionController::class, 'store'])->name('transaction.store');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:super-admin,admin-outlet,supervisor', 'brand.setup'])->group(function () {
    Route::get('/setup-brand', [\App\Http\Controllers\Admin\BrandSetupController::class, 'index'])->name('setup.brand')->withoutMiddleware('brand.setup');
    Route::post('/setup-brand', [\App\Http\Controllers\Admin\BrandSetupController::class, 'store'])->name('setup.brand.store')->withoutMiddleware('brand.setup');


    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::controller(OutletController::class)->prefix('outlet')->name('outlet.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/data', 'data')->name('data');
        Route::get('/create', 'create')->name('create');
        Route::get('/{id}/detail', 'detail')->name('detail');
        Route::post('/', 'store')->name('store');
        Route::get('/{outlet}/edit', 'edit')->name('edit');
        Route::put('/{outlet}', 'update')->name('update');
        Route::delete('/{outlet}', 'destroy')->name('destroy');
    });

    Route::controller(CustomerController::class)->prefix('customer')->name('customer.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/data', 'data')->name('data');
        Route::get('/create', 'create')->name('create');
        Route::get('/generate-code', 'generateCode')->name('generate-code');
        Route::post('/', 'store')->name('store');
        Route::get('/{customer}/edit', 'edit')->name('edit');
        Route::put('/{customer}', 'update')->name('update');
        Route::delete('/{customer}', 'destroy')->name('destroy');
    });

    Route::controller(EmployeeController::class)->prefix('employee')->name('employee.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/data', 'data')->name('data');
        Route::get('/create', 'create')->name('create');
        Route::get('/generate-code', 'generateCode')->name('generate-code');
        Route::post('/', 'store')->name('store');
        Route::get('/{employee}/edit', 'edit')->name('edit');
        Route::put('/{employee}', 'update')->name('update');
        Route::delete('/{employee}', 'destroy')->name('destroy');
    });

    Route::controller(UserController::class)->prefix('user')->name('user.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/data', 'data')->name('data');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{user}/edit', 'edit')->name('edit');
        Route::put('/{user}', 'update')->name('update');
        Route::delete('/{user}', 'destroy')->name('destroy');
    });

    Route::controller(AttendanceController::class)->prefix('attendance')->name('attendance.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/data', 'data')->name('data');
    });

    Route::controller(TransactionController::class)->prefix('transaction')->name('transaction.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/data', 'data')->name('data');
        Route::get('/export/excel', 'exportExcel')->name('export.excel');
        Route::get('/export/pdf', 'exportPdf')->name('export.pdf');
        Route::get('/create', 'create')->name('create');
        Route::get('/{id}/detail', 'detail')->name('detail');
        Route::post('/', 'store')->name('store');
        Route::patch('/{transaction}/status', 'updateStatus')->name('status');
        Route::patch('/{transaction}/payment', 'updatePayment')->name('payment');
        Route::delete('/{transaction}', 'destroy')->name('destroy');
    });

    Route::controller(ExpenseController::class)->prefix('expense')->name('expense.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/data', 'data')->name('data');
        Route::get('/export/excel', 'exportExcel')->name('export.excel');
        Route::get('/export/pdf', 'exportPdf')->name('export.pdf');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{expense}/edit', 'edit')->name('edit');
        Route::put('/{expense}', 'update')->name('update');
        Route::delete('/{expense}', 'destroy')->name('destroy');
    });

    Route::controller(SalaryController::class)->prefix('salary')->name('salary.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{employee_code}/pdf', 'exportPdf')->name('pdf');
    });
    Route::controller(ProductController::class)->prefix('product')->name('product.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/data', 'data')->name('data');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{product}/edit', 'edit')->name('edit');
        Route::put('/{product}', 'update')->name('update');
        Route::delete('/{product}', 'destroy')->name('destroy');
    });
});

Route::prefix('superadmin')->name('superadmin.')->middleware(['auth', 'role:super-admin'])->group(function () {
    Route::controller(TenantController::class)->prefix('tenant')->name('tenant.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
    });
});

// Auth Routes Explicit
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login')->middleware('guest');
    Route::post('/login', 'login')->middleware('guest');
    Route::post('/logout', 'logout')->name('logout')->middleware('auth');
});
