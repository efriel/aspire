<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\LoanController;
use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\AccountingController;
use App\Http\Controllers\API\SavingsController;

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

Route::prefix('savings')->group(function () {
    Route::controller(SavingsController::class)->group(function () {
        Route::get('info', 'checkSavingInfo');
    });
});

Route::prefix('loan')->group(function () {
    Route::controller(LoanController::class)->group(function () {
        Route::post('simulation', 'simulationLoan');
        Route::post('request', 'requestLoan');
        Route::get('info', 'checkLoanInfo');
        Route::post('repayment', 'repaymentLoan');
    });
});

Route::group(['middleware' => ['admin']], function () {
    Route::prefix('admin')->group(function () {
        Route::controller(AdminController::class)->group(function () {
            Route::post('transactions', 'transactionList');
            Route::post('approve', 'approve');
        });
    });
});