<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\LoanController;
use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\AccountingController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('user')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('login', 'login');
        Route::post('register', 'register');
        Route::post('logout', 'logout');
        Route::post('refresh', 'refresh');
    });
});

Route::prefix('loan')->group(function () {
    Route::controller(LoanController::class)->group(function () {
        Route::post('simulation', 'simulation');
    });
});

Route::prefix('admin')->group(function () {
    Route::controller(AdminController::class)->group(function () {
        Route::post('transactions', 'transactions');
    });
});

Route::group(['middleware' => ['admin']], function () {
    Route::prefix('accounting')->group(function () {
        Route::controller(AccountingController::class)->group(function () {
            Route::post('transactions', 'transactions');
        });
    });
});