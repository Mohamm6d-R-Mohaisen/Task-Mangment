<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\Auth\LoginController;
use App\Http\Controllers\Frontend\Auth\RegisterController;
use App\Http\Controllers\Frontend\HomeController;
 /* ------------------------------------- Auth Routes --------------------------------- */
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login'])->name('login.submit');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('register', [RegisterController::class, 'create'])->name('register');
Route::post('register', [RegisterController::class, 'store'])->name('register.submit');

Route::get('/verify-otp', [RegisterController::class, 'showVerifyForm'])->name('show.verify-otp');
Route::post('/verify-otp', [RegisterController::class, 'verifyOtp'])->name('verify-otp');
Route::post('/resend-otp', [RegisterController::class, 'resendOtp'])->name('resend.otp');
 /* ------------------------------------- Auth Routes --------------------------------- */



Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/projects/{id}', [HomeController::class, 'project_details'])->name('project_details');

