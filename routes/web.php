<?php

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DoctorController;

Route::get('/', function () {
    return auth()->check()
    ? redirect()->route('home')
    : redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

    Route::post('/login', [AuthController::class, 'login'])->name('login.stores');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');

    Route::post('/register', [AuthController::class, 'register'])->name('register.store');

});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');


Route::view('/home', 'home')->middleware('auth')->name('home');


Route::middleware('auth')->group(function() {

    Route::resource('appointments', AppointmentController::class)->except(['edit', 'update']);

    Route::get('/doctors.index', [DoctorController::class, 'index'])->name('doctors');
});
