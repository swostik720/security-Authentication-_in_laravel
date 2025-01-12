<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;

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

Route::get('/', [HomeController::class, 'index']); // The root route (index page)

Route::get('/register', [AuthController::class, 'showRegisterForm']);
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/auth/google', [AuthController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

// Define dashboard routes
Route::middleware('auth')->group(function () {
    // Admin dashboard route
    Route::get('/admin/dashboard', [AdminController::class, 'showDashboard'])->name('admin.dashboard');

    // User dashboard route
    Route::get('/user/dashboard', [UserController::class, 'showDashboard'])->name('user.dashboard');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');

Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm']);
Route::post('/forgot-password', [AuthController::class, 'sendPasswordResetLink']);

Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword']);


