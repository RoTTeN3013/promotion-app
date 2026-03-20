<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\UserSubmissionController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', [AuthController::class, 'Register'])->name('register');
Route::get('/login', [AuthController::class, 'Login'])->name('login');
Route::post('/register', [AuthController::class, 'RegisterUser'])->name('register.user');
Route::post('/login', [AuthController::class, 'LogUserIn'])->name('login.user');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'Show'])->name('dashboard');
    Route::get('/user-submissions', [UserSubmissionController::class, 'index'])->name('user-submissions');
    Route::get('/user-submissions/create', [UserSubmissionController::class, 'create'])->name('create-user-submission');
    Route::post('/user-submissions', [UserSubmissionController::class, 'store'])->name('store-user-submission');
    Route::get('/view-user-submission/{submission}', [UserSubmissionController::class, 'show'])->name('view-user-submission');
    Route::post('/log-out', [AuthController::class, 'logUserOut'])->name('logout');
    Route::get('/exports/{export}/download', [ExportController::class, 'download'])->name('exports.download');
});