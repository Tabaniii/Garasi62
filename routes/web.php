<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\AuthenticateMiddleware;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CommentController;

Route::get('/', [IndexController::class, 'index'])->name('home');
Route::get('/index', [IndexController::class, 'index'])->name('index');
Route::get('/index.html', [IndexController::class, 'index'])->name('index.html');

// Public Blog Routes
Route::get('/blog', [BlogController::class, 'index'])->name('blog');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

// Public Comment Routes (auth checked in controller)
Route::post('/blog/{slug}/comment', [CommentController::class, 'store'])->name('comments.store');
Route::view('/about', 'about')->name('about');
//car
Route::get('/car', [CarController::class, 'show'])->name('cars');
Route::get('/car/{id}', [CarController::class, 'showDetail'])->name('car.details');
Route::view('/contact', 'contact')->name('contact');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

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
    
    // Blog Management (Admin)
    Route::prefix('admin/blogs')->name('blogs.admin.')->group(function () {
        Route::get('/', [BlogController::class, 'adminIndex'])->name('index');
        Route::get('/create', [BlogController::class, 'create'])->name('create');
        Route::post('/', [BlogController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [BlogController::class, 'edit'])->name('edit');
        Route::put('/{id}', [BlogController::class, 'update'])->name('update');
        Route::delete('/{id}', [BlogController::class, 'destroy'])->name('destroy');
    });
    
    // Comment Management (Admin)
    Route::prefix('admin/comments')->name('comments.admin.')->group(function () {
        Route::get('/', [CommentController::class, 'adminIndex'])->name('index');
        Route::post('/{id}/approve', [CommentController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [CommentController::class, 'reject'])->name('reject');
        Route::delete('/{id}', [CommentController::class, 'destroy'])->name('destroy');
    });
});

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
