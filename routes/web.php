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
use App\Http\Controllers\ContactController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\CarApprovalController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ReportController;

Route::get('/', [IndexController::class, 'index'])->name('home');
Route::get('/index', [IndexController::class, 'index'])->name('index');
Route::get('/index.html', [IndexController::class, 'index'])->name('index.html');

// Public Blog Routes
Route::get('/blog', [BlogController::class, 'index'])->name('blog');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

// Public Comment Routes (auth checked in controller)
Route::post('/blog/{slug}/comment', [CommentController::class, 'store'])->name('comments.store');
Route::get('/about', [AboutController::class, 'index'])->name('about');
//car
Route::get('/car', [CarController::class, 'show'])->name('cars');
Route::get('/car/{id}', [CarController::class, 'showDetail'])->name('car.details');

// Contact Routes (Auth checked in controller)
Route::get('/contact', function () {
    return view('contact');
})->name('contact');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

// CRUD Mobil (Protected by auth and role middleware)
    Route::middleware(['auth'])->group(function () {
        // Cars index - Admin and Seller only
        Route::get('/cars', [CarController::class, 'index'])->middleware('role:admin,seller')->name('cars.index');

        // Cars create - Admin and Seller only
        Route::get('/cars/create', [CarController::class, 'create'])->middleware('role:admin,seller')->name('cars.create');
        Route::post('/cars', [CarController::class, 'store'])->middleware('role:admin,seller')->name('cars.store');

        // Cars edit/update/delete - Admin and Seller (with ownership check)
        Route::get('/cars/{id}/edit', [CarController::class, 'edit'])->middleware('role:admin,seller')->name('cars.edit');
        Route::put('/cars/{id}', [CarController::class, 'update'])->middleware('role:admin,seller')->name('cars.update');
        Route::delete('/cars/{id}', [CarController::class, 'destroy'])->middleware('role:admin,seller')->name('cars.destroy');
    
    // Users Management (Admin Only)
    Route::prefix('users')->name('users.')->middleware('role:admin')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
    });
    
    // Blog Management (Admin Only)
    Route::prefix('admin/blogs')->name('blogs.admin.')->middleware('role:admin')->group(function () {
        Route::get('/', [BlogController::class, 'adminIndex'])->name('index');
        Route::get('/create', [BlogController::class, 'create'])->name('create');
        Route::post('/', [BlogController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [BlogController::class, 'edit'])->name('edit');
        Route::put('/{id}', [BlogController::class, 'update'])->name('update');
        Route::delete('/{id}', [BlogController::class, 'destroy'])->name('destroy');
    });
    
    // Testimonials Management (Admin Only)
    Route::prefix('admin/testimonials')->name('testimonials.admin.')->middleware('role:admin')->group(function () {
        Route::get('/', [TestimonialController::class, 'index'])->name('index');
        Route::get('/create', [TestimonialController::class, 'create'])->name('create');
        Route::post('/', [TestimonialController::class, 'store'])->name('store');
        Route::get('/{testimonial}/edit', [TestimonialController::class, 'edit'])->name('edit');
        Route::put('/{testimonial}', [TestimonialController::class, 'update'])->name('update');
        Route::delete('/{testimonial}', [TestimonialController::class, 'destroy'])->name('destroy');
    });
    
    // Comment Management (Admin Only)
    Route::prefix('admin/comments')->name('comments.admin.')->middleware('role:admin')->group(function () {
        Route::get('/', [CommentController::class, 'adminIndex'])->name('index');
        Route::post('/{id}/approve', [CommentController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [CommentController::class, 'reject'])->name('reject');
        Route::delete('/{id}', [CommentController::class, 'destroy'])->name('destroy');
    });

    // Car Approval Management (Admin Only)
    Route::prefix('admin/car-approvals')->name('admin.car-approvals.')->middleware('role:admin')->group(function () {
        Route::get('/', [CarApprovalController::class, 'index'])->name('index');
        Route::get('/history', [CarApprovalController::class, 'history'])->name('history');
        Route::get('/{car}', [CarApprovalController::class, 'show'])->name('show');
        Route::post('/{car}/approve', [CarApprovalController::class, 'approve'])->name('approve');
        Route::post('/{car}/reject', [CarApprovalController::class, 'reject'])->name('reject');
    });

    // Wishlist Management (Buyer Only)
    Route::prefix('wishlist')->name('wishlist.')->middleware('role:buyer')->group(function () {
        Route::post('/{car}', [WishlistController::class, 'store'])->name('store');
        Route::delete('/{car}', [WishlistController::class, 'destroy'])->name('destroy');
    });

    // Reports Management
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::post('/{car}', [ReportController::class, 'store'])->name('store');
    });

    // Reports Management (Admin Only)
    Route::prefix('admin/reports')->name('admin.reports.')->middleware('role:admin')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/{report}', [ReportController::class, 'show'])->name('show');
        Route::put('/{report}', [ReportController::class, 'update'])->name('update');
    });

    // Reports Management (Seller Only)
    Route::prefix('seller/reports')->name('seller.reports.')->middleware('role:seller')->group(function () {
        Route::get('/', [ReportController::class, 'sellerIndex'])->name('index');
    });
});

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
