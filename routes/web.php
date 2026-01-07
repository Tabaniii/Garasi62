<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\AuthenticateMiddleware;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\CarController;

Route::view('/', 'index')->name('home');
Route::view('/index', 'index')->name('index');
Route::view('/index.html', 'index')->name('index.html');
Route::view('/blog', 'blog')->name('blog');
Route::view('/blog-details', 'blog-details')->name('blog.details');
Route::view('/about', 'about')->name('about');
//car
Route::get('/car', [CarController::class, 'show'])->name('cars');
Route::view('/car-details', 'car-details')->name('car.details');
Route::view('/contact', 'contact')->name('contact');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');




Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
