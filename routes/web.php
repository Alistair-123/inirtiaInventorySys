<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');


    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Use custom middleware for debugging
Route::middleware(['auth'])->group(function () {
    Route::group(['middleware' => function ($request, $next) {
        if (!$request->user() || !$request->user()->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }
        return $next($request);
    }], function () {
        // Admin routes
        Route::get('/admin/products', [ProductController::class, 'adminOnly'])->name('admin.products.index');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
        Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
        Route::delete('/admin/{admin}', [AdminController::class, 'destroy'])->name('admin.destroy');
    });
});

// Test route to check roles
Route::get('/test-roles', function () {
    // Create roles if they don't exist
    if (!\Spatie\Permission\Models\Role::where('name', 'admin')->exists()) {
        \Spatie\Permission\Models\Role::create(['name' => 'admin', 'guard_name' => 'web']);
    }
    if (!\Spatie\Permission\Models\Role::where('name', 'user')->exists()) {
        \Spatie\Permission\Models\Role::create(['name' => 'user', 'guard_name' => 'web']);
    }

    // Get current user and assign admin role
    $user = auth()->user();
    if ($user) {
        $roles = $user->getRoleNames()->toArray();
        $user->assignRole('admin');

        return "User: {$user->email}, Previous roles: " . implode(', ', $roles) .
               ", Current roles: " . implode(', ', $user->getRoleNames()->toArray());
    }

    return "Not logged in!";
});

require __DIR__ . '/auth.php';
