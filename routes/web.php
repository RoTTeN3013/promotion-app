<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\UserSubmissionController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'Register'])->name('register');
    Route::get('/login', [AuthController::class, 'Login'])->name('login');
});
Route::post('/register', [AuthController::class, 'RegisterUser'])->name('register.user');
Route::post('/login', [AuthController::class, 'LogUserIn'])->name('login.user');

Route::middleware(['auth'])->group(function () {
    Route::get('/user-dashboard', [DashboardController::class, 'Show'])->name('user-dashboard');
    Route::get('/contact', [ContactController::class, 'create'])->name('contact.create');
    Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');
    Route::get('/profile', [UserProfileController::class, 'edit'])->name('user-profile.edit');
    Route::put('/profile', [UserProfileController::class, 'update'])->name('user-profile.update');
    Route::get('/user-submissions', [UserSubmissionController::class, 'index'])->name('user-submissions');
    Route::get('/user-submissions/create', [UserSubmissionController::class, 'create'])->name('create-user-submission');
    Route::post('/user-submissions', [UserSubmissionController::class, 'store'])->name('store-user-submission');
    Route::delete('/user-submissions/{submission}', [UserSubmissionController::class, 'destroy'])->name('delete-user-submission');
    Route::post('/user-submissions/{submission}/appeal', [UserSubmissionController::class, 'appeal'])->name('appeal-user-submission');
    Route::get('/view-user-submission/{submission}', [UserSubmissionController::class, 'show'])->name('view-user-submission');
    Route::post('/log-out', [AuthController::class, 'logUserOut'])->name('logout');
    Route::get('/promotions/{promotion}', [DashboardController::class, 'showPromotion'])->name('promotion.show');
    Route::post('/user-submissions/{submission}/mark-updated', [UserSubmissionController::class, 'markUpdated'])->name('mark-updated-user-submission');
});

Route::middleware(['auth:admin'])->group(function () {
    Route::get('/exports/{export}/download', [ExportController::class, 'download'])->name('exports.download');
});