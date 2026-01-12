<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\AuthenticateMiddleware;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\IndexController;

Route::get('/', [IndexController::class, 'index'])->name('home');
Route::get('/index', [IndexController::class, 'index'])->name('index');
Route::get('/index.html', [IndexController::class, 'index'])->name('index.html');
Route::view('/blog', 'blog')->name('blog');
Route::view('/blog-details', 'blog-details')->name('blog.details');
Route::view('/about', 'about')->name('about');
//car
Route::get('/car', [CarController::class, 'show'])->name('cars');
Route::get('/car/{id}', [CarController::class, 'showDetail'])->name('car.details');
Route::view('/contact', 'contact')->name('contact');

// CRUD Mobil (Protected by auth middleware)
Route::middleware('auth')->group(function () {
    Route::get('/cars', [CarController::class, 'index'])->name('cars.index');
    Route::get('/cars/create', [CarController::class, 'create'])->name('cars.create');
    Route::post('/cars', [CarController::class, 'store'])->name('cars.store');
    Route::get('/cars/{id}/edit', [CarController::class, 'edit'])->name('cars.edit');
    Route::put('/cars/{id}', [CarController::class, 'update'])->name('cars.update');
    Route::delete('/cars/{id}', [CarController::class, 'destroy'])->name('cars.destroy');
    
    // Users
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
});

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
