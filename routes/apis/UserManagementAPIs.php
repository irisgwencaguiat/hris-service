<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LogController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserActivityLogController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserTypeController;
use App\Http\Controllers\RefUserClassificationController;

Route::group(['middleware' => 'auth:api'], function ($router) {
    // Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    // Route::post('refresh', [AuthController::class, 'refresh'])->name('refresh');

    // Route::get('/users', [UserController::class, 'index'])->name('users');
    // Route::1get('/users/{id}', [UserController::class, 'show'])->name('users.show');
    // Route::post('/users', [UserController::class, 'store'])->name('users.store');
    // Route::post('/users/update/{id}', [UserController::class, 'update',])->name('users.update');

    // Route::get('/user/types', [UserTypeController::class, 'index'])->name('user.types');
    // Route::get('/user/types/{id}', [UserTypeController::class, 'show'])->name('user.types.show');
    // Route::post('/user/types', [UserTypeController::class, 'store'])->name('user.types.store');
    // Route::post( '/user/types/update/{id}', [UserTypeController::class, 'update'])->name('user.types.update');

    /*Route::get('/employees', [EmployeeController::class, 'index'])->name(
        'employees'
    );
    Route::get('/employees/{id}', [EmployeeController::class, 'show'])->name(
        'employees.show'
    );
    Route::post('/employees', [EmployeeController::class, 'store'])->name(
        'employees.store'
    );
    Route::match(['put', 'patch'], '/employees/update/{id}', [
        EmployeeController::class,
        'update',
    ])->name('employees.update');*/

    /*Route::get('/designations', [DesignationController::class, 'index'])->name(
        'designations'
    );
    Route::get('/designations/{id}', [
        DesignationController::class,
        'show',
    ])->name('designations.show');
    Route::post('/designations', [DesignationController::class, 'store'])->name(
        'designations.store'
    );
    Route::match(['put', 'patch'], '/designations/update/{id}', [
        DesignationController::class,
        'update',
    ])->name('designations.update');*/

    Route::get('/logs', [LogController::class, 'index'])->name('logs');
    Route::get('/logs/{id}', [LogController::class, 'show'])->name('logs.show');

    // change password
    Route::patch('user/{id}/change/password', [
        EmployeeController::class,
        'changePassword',
    ])->name('change.password');
});

Route::group(['middleware' => 'auth:api'], function ($router) {
    Route::get('/user-activity-logs', [UserActivityLogController::class, 'index'])->name('user_activity_logs');
    Route::post('/user-activity-logs', [UserActivityLogController::class, 'store'])->name('user_activity_logs.store');

    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::get('/users/{user_id}', [UserController::class, 'show'])->name('users.show');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::patch('/users/{user_id}', [UserController::class, 'update'])->name('users.patch');
    Route::delete('/users/{user_id}', [UserController::class, 'delete'])->name('users.delete');

    Route::post('/users/{user_id}/deactivate', [UserController::class, 'deactivate'])->name('users.deactivate');
    Route::post('/users/{user_id}/activate', [UserController::class, 'activate'])->name('users.activate');
    Route::post('/users/{user_id}/ban', [UserController::class, 'ban'])->name('users.ban');
    Route::post('/users/{user_id}/expire', [UserController::class, 'expire'])->name('users.expire');
    Route::post('/users/{user_id}/reset-password', [UserController::class, 'resetPassword'])->name('users.resetPassword');

    Route::get('/users/statistics/count', [UserController::class, 'statisticsCount'])->name('users.statisticsCount');
    Route::get('/users/statistics/count-by-types', [UserController::class, 'statisticsCountByTypes'])->name('users.statisticsCountByTypes');
    Route::get('/users/statistics/count-by-classifications', [UserController::class, 'statisticsCountByClassifications'])->name('users.statisticsCountByClassifications');

    Route::get('/user-types', [UserTypeController::class, 'index'])->name('user_types');
    Route::get('/references/user-classifications', [RefUserClassificationController::class, 'index'])->name('user_classifications');
});


/**
 * Pages
 */
use App\Http\Controllers\reference\PageController;
use App\Http\Controllers\UserTypePageController;
use App\Http\Controllers\reference\PageTypeController;

Route::group(['middleware' => 'auth:api'], function ($router) {
    
    Route::resource('/pages', PageController::class);

    Route::resource('/user-type-pages', UserTypePageController::class);
    
    Route::get('/auth-modules', [UserTypePageController::class, 'getAuthModules']);

    Route::get('/reference/page-types', [PageTypeController::class, 'index']);

    Route::get('/default-route', [UserTypePageController::class, 'getDefaultRoute']);
});