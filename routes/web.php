<?php

use App\Models\User; 
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;

// 1. Welcome Page
Route::get('/', function () {
    return redirect()->route('login');
});

// 2. Waiting Room
Route::get('/waiting-approval', function () {
    return view('auth.waiting-approval');
})->middleware(['auth'])->name('waiting.approval');

// 3. Protected Routes
Route::middleware(['auth', 'verified', 'approved'])->group(function () {
    
    // CLIENT ROUTE
    Route::get('/dashboard', function () {
        return view('admin.clientdashboard');
    })->name('dashboard');

    // Profile Settings
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- ADMIN ONLY ROUTES ---
    Route::middleware(['admin'])->group(function () {
        
        // Admin Dashboard (Overview only)
        Route::get('/admin/dashboard', function () {
            return view('admin.admindashboard'); 
        })->name('admin.dashboard');

        // User Management (Points to Controller for logic)
        Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
        
        Route::post('/admin/users/approve/{id}', [UserController::class, 'approve'])->name('admin.users.approve');

        // Route for the Approve action
        Route::post('/admin/users/{id}/approve', [UserController::class, 'approve'])->name('admin.users.approve');

        // Route for the Decline action (the pop-up)
        Route::post('/admin/users/decline', [UserController::class, 'decline'])->name('admin.users.decline');

        // Add these to your routes/web.php
        Route::post('/admin/users/restore', [UserController::class, 'restore'])->name('admin.users.restore');
        Route::post('/admin/users/archive', [UserController::class, 'archive'])->name('admin.users.archive');

        // Add this inside your admin middleware group
        Route::post('/admin/users/revoke', [UserController::class, 'revoke'])->name('admin.users.revoke');
    });
});

require __DIR__.'/auth.php';