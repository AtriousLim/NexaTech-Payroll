<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;


/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

Route::get('/', fn () => redirect()->route('login'));

Route::get('/login', [LoginController::class, 'index'])
    ->name('login');

Route::post('/login/admin', [LoginController::class, 'adminLogin'])
    ->name('login.admin');

Route::post('/login/employee', [LoginController::class, 'employeeLogin'])
    ->name('login.employee');

Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:admin'])->group(function () {

    Route::get('/admin/dashboard',
        [DashboardController::class,'dashboard'])
        ->name('admin.dashboard');

    Route::get('/admin/employees',
        [DashboardController::class,'employees'])
        ->name('admin.employees');

    Route::get('/admin/payroll',
        [DashboardController::class,'payroll'])
        ->name('admin.payroll');

    Route::get('/admin/payroll-history',
        [DashboardController::class,'payrollHistory'])
        ->name('admin.payroll-history');

    Route::get('/admin/activity-log',
        [DashboardController::class,'activityLog'])
        ->name('admin.activity-log');

    Route::get('/admin/add-admin',
        [DashboardController::class,'addAdmin'])
        ->name('admin.add-admin');

});

use Illuminate\Http\Request;

Route::get('/session-test', function (Request $request) {
    $request->session()->put('test', 'working');

    return [
        'session_id' => $request->session()->getId(),
        'value' => $request->session()->get('test'),
    ];
});