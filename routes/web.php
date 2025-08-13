<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminUsersController;
use App\Http\Controllers\AdminRolesController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;

// Public routes
Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'login'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('login.authenticate');
    Route::get('/register', [RegisterController::class, 'register'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
});

// Public post routes
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');

// Protected routes (require authentication)
Route::middleware('auth')->group(function () {
    // User profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    
    // Admin routes
    Route::prefix('admin')->name('admin.')->group(function () {
        // Users management
        Route::get('/users', [AdminUsersController::class, 'index'])->name('users.index');
        Route::get('/users/{user}/edit', [AdminUsersController::class, 'edit'])->name('users.edit');
        
        // Roles management
        Route::get('/roles', [AdminRolesController::class, 'index'])->name('roles.index');
        
        // Role assignment
        Route::post('/users/{user}/roles/assign', [AdminUsersController::class, 'assignRole'])
            ->name('users.roles.assign');
            
        Route::delete('/users/{user}/roles/{role}', [AdminUsersController::class, 'removeRole'])
            ->name('users.roles.remove');
    });
    
    // Post management - specific routes first
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    
    // Logout Route
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// Catch-all post show route (keep this last)
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
