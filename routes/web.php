<?php

use App\Http\Controllers\CustomerDashboard;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/login', [LoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login-action', [LoginController::class, 'action'])->name('login-action')->middleware('guest');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'check.session'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::get('get-users', [UserController::class, 'getUsers'])->name('get-users');

    Route::prefix('/customer')->name('customer.')->group(function () {
        Route::get('dashboard', [CustomerDashboard::class, 'index'])->name('dashboard');
        Route::get('get-pins', [CustomerDashboard::class, 'getPins'])->name('get-pins');
        Route::post('update-pins', [CustomerDashboard::class, 'updatePins'])->name('update-pins');
        Route::get('get-device-status', [CustomerDashboard::class, 'getDeviceStatus'])->name('get-device-status');
        Route::get('profile/{id}', [UserController::class, 'profile'])->name('profile');
    });


    Route::prefix('/setting')->group(function () {
        Route::get('/menus', [MenuController::class, 'index'])->name('setting.menus');
        Route::post('/menus', [MenuController::class, 'store'])->name('setting.menus.store');
        Route::get('/menus/{id}', [MenuController::class, 'edit'])->name('setting.menus.edit');
        Route::post('/menus/destroy', [MenuController::class, 'destroy'])->name('setting.menus.destroy');
        Route::get('/menus/parents/children', [MenuController::class, 'parentMenu'])->name('setting.menus.parents.children');
        Route::put('/menus/update', [MenuController::class, 'update'])->name('setting.menus.update');

        Route::get('/access-utilities/roles', [RoleController::class, 'index'])->name('setting.access-utilities.roles');
        Route::get('/access-utilities/roles/create', [RoleController::class, 'create'])->name('setting.access-utilities.roles.create');
        Route::post('/access-utilities/roles/store', [RoleController::class, 'store'])->name('setting.access-utilities.roles.store');
        Route::get('/access-utilities/roles/edit/{id}', [RoleController::class, 'edit'])->name('setting.access-utilities.roles.edit');
        Route::post('/access-utilities/roles/update', [RoleController::class, 'update'])->name('setting.access-utilities.roles.update');
        Route::get('/access-utilities/roles/destroy/{id}', [RoleController::class, 'destroy'])->name('setting.access-utilities.roles.destroy');

        Route::get('/access-utilities/permissions', [PermissionController::class, 'index'])->name('setting.access-utilities.permissions');
        Route::get('/access-utilities/permissions/create', [PermissionController::class, 'create'])->name('setting.access-utilities.permissions.create');
        Route::post('/access-utilities/permissions/store', [PermissionController::class, 'store'])->name('setting.access-utilities.permissions.store');
        Route::get('/access-utilities/permissions/edit/{id}', [PermissionController::class, 'edit'])->name('setting.access-utilities.permissions.edit');
        Route::post('/access-utilities/permissions/update', [PermissionController::class, 'update'])->name('setting.access-utilities.permissions.update');
        Route::get('/access-utilities/permissions/destroy/{id}', [PermissionController::class, 'destroy'])->name('setting.access-utilities.permissions.destroy');


        Route::get('/users', [UserController::class, 'index'])->name('setting.users');
        Route::post('/users', [UserController::class, 'store'])->name('setting.users.store');
        Route::get('/users/roles', [UserController::class, 'getRoles'])->name('setting.users.roles');
        Route::put('/users/update', [UserController::class, 'update'])->name('setting.users.update');
        Route::get('/users/edit/{id}', [UserController::class, 'edit'])->name('setting.users.edit');
        Route::get('/users/destroy/{id}', [UserController::class, 'destroy'])->name('setting.users.destroy');
    });

    Route::prefix('/device')->group(function () {
        Route::get('/config', [DeviceController::class, 'index'])->name('device.config');
        Route::post('/config/store', [DeviceController::class, 'store'])->name('device.config.store');
        Route::get('/config/destroy/{id}', [DeviceController::class, 'destroy'])->name('device.config.destroy');
        Route::get('/config/edit/{id}', [DeviceController::class, 'edit'])->name('device.config.edit');
        Route::post('/config/update', [DeviceController::class, 'update'])->name('device.config.update');
    });
});
