<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\Customer;
use App\Http\Controllers\api\GetPins;
use App\Http\Controllers\DeviceController;
use App\Http\Middleware\JwtMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::prefix('/v1')->name('v1.')->group(function () {

    Route::post('login', [AuthController::class, 'generatejwt'])->name('login');

    Route::get('get-pins', [Customer::class, 'index'])->name('get-pins')->middleware(JwtMiddleware::class);
    Route::post('update-pins', [Customer::class, 'updatePins'])->name('update-pins')->middleware(JwtMiddleware::class);

    Route::get('control-api', [DeviceController::class, 'controlApi'])->name('control-api');
});
