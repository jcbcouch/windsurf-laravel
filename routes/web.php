<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminUsersController;
use App\Http\Controllers\AdminRolesController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\VideoController;

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

// Public video routes
Route::get('/videos', [VideoController::class, 'index'])->name('videos.index');

// Comment routes
Route::middleware('auth')->group(function () {
    Route::post('/posts/{post}/comments', [PostController::class, 'storeComment'])->name('posts.comments.store');
    Route::delete('/posts/{post}/comments/{comment}', [PostController::class, 'destroyComment'])->name('posts.comments.destroy');
    
    // Like routes
    Route::post('/posts/{post}/like', [PostController::class, 'like'])->name('posts.like');
    Route::delete('/posts/{post}/like', [PostController::class, 'unlike'])->name('posts.unlike');
});

// Protected routes (require authentication)
Route::middleware('auth')->group(function () {
    // User profile and roles
    Route::prefix('profile')->name('profile.')->group(function () {
        // Current user's profile
        Route::get('/my-profile', [ProfileController::class, 'index'])->name('index');
        
        // View specific user's profile
        Route::get('/{user}', [ProfileController::class, 'show'])->name('show');
        
        // Profile picture routes
        Route::get('/picture/edit', [ProfileController::class, 'editPicture'])->name('picture.edit');
        Route::patch('/picture', [ProfileController::class, 'updatePicture'])->name('picture.update');
        Route::delete('/picture', [ProfileController::class, 'destroyPicture'])->name('picture.destroy');
        
        // Background color routes
        Route::get('/background/edit', [ProfileController::class, 'editBackground'])->name('background.edit');
        Route::patch('/background', [ProfileController::class, 'updateBackground'])->name('background.update');
    });
    
    // Debug route to show current user's roles
    Route::get('/my-roles', function() {
        $user = auth()->user();
        return [
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'has_admin_role' => $user->hasRole('Administrator'),
            'roles' => $user->roles->map(function($role) {
                return [
                    'id' => $role->id,
                    'name' => $role->name,
                    'display_name' => $role->display_name,
                    'slug' => $role->slug,
                    'created_at' => $role->created_at
                ];
            })
        ];
    })->name('my.roles');
    
    // Admin routes - protected by admin middleware
    Route::middleware([\App\Http\Middleware\AdminMiddleware::class])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
        // Users management
        Route::get('/users', [AdminUsersController::class, 'index'])->name('users.index');
        Route::get('/users/{user}/edit', [AdminUsersController::class, 'edit'])->name('users.edit');
        
        // Roles management
        Route::get('/roles', [AdminRolesController::class, 'index'])->name('roles.index');
        Route::get('/roles/create', [AdminRolesController::class, 'create'])->name('roles.create');
        Route::post('/roles', [AdminRolesController::class, 'store'])->name('roles.store');
        Route::get('/roles/{role}/edit', [AdminRolesController::class, 'edit'])->name('roles.edit');
        Route::put('/roles/{role}', [AdminRolesController::class, 'update'])->name('roles.update');
        Route::delete('/roles/{role}', [AdminRolesController::class, 'destroy'])->name('roles.destroy');
        
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
    
    // Video Routes
    Route::middleware(['verified'])->group(function () {
        Route::get('/videos/create', [VideoController::class, 'create'])->name('videos.create');
        Route::post('/videos', [VideoController::class, 'store'])->name('videos.store');
        Route::delete('/videos/{video}', [VideoController::class, 'destroy'])->name('videos.destroy');
    });
    
    // Public video view route
    Route::get('/videos/{video}', [VideoController::class, 'show'])->name('videos.show');
    
    // Logout Route
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// Catch-all post show route (keep this last)
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
