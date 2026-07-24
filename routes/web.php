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

    Route::get('/admin/employees/create',
        [DashboardController::class,'createEmployee'])
        ->name('admin.employees.create');

    Route::post('/admin/employees',
        [DashboardController::class,'storeEmployee'])
        ->name('admin.employees.store');

    Route::get('/admin/employees/{employee}',
        [DashboardController::class,'viewEmployee'])
        ->name('admin.employees.view');

    Route::get('/admin/employees/{employee}/edit',
        [DashboardController::class,'editEmployee'])
        ->name('admin.employees.edit');

    Route::patch('/admin/employees/{employee}',
        [DashboardController::class,'updateEmployee'])
        ->name('admin.employees.update');

    Route::delete('/admin/employees/{employee}',
        [DashboardController::class,'destroyEmployee'])
        ->name('admin.employees.destroy');

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

    Route::post('/admin/add-admin',
        [DashboardController::class,'storeAdmin'])
        ->name('admin.add-admin.store');

});

use Illuminate\Http\Request;

Route::get('/session-test', function (Request $request) {
    $request->session()->put('test', 'working');

    return [
        'session_id' => $request->session()->getId(),
        'value' => $request->session()->get('test'),
    ];
});