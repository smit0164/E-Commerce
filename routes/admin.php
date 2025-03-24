<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Admin\ProductController;
use Illuminate\Support\Facades\Route;

// Admin Guest Routes (Login)
Route::middleware('guest:admin')->group(function () {
    Route::prefix('admin')->group(function () {
        Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
        Route::post('login', [AdminAuthController::class, 'login']);
    });
});

// Admin Orders Routes
Route::prefix('admin/orders')->middleware('auth:admin')->group(function () {
    Route::get('/', [OrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/{order}', [OrderController::class, 'show'])->name('admin.orders.show');
    Route::get('/export/csv', [OrderController::class, 'exportCsv'])->name('admin.orders.exportCsv');
    Route::get('/{order}/edit', [OrderController::class, 'edit'])->name('admin.orders.edit');
    Route::put('/{order}', [OrderController::class, 'update'])->name('admin.orders.update');
});

// Admin Authenticated Routes
Route::middleware('auth:admin')->group(function () {
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        
        // Categories Routes
        Route::prefix('categories')->group(function () {
            // List active categories
            Route::get('/', [CategoryController::class, 'index'])->name('admin.categories.index');
            Route::get('/trashed', [CategoryController::class, 'trashed'])->name('admin.categories.trashed');
            // Create category
            Route::get('/create', [CategoryController::class, 'create'])->name('admin.categories.create');
            Route::post('/store', [CategoryController::class, 'store'])->name('admin.categories.store');
        
            // Show category details
            Route::get('/{slug}', [CategoryController::class, 'show'])->name('admin.categories.show');
        
            // Edit category
            Route::get('/{slug}/edit', [CategoryController::class, 'edit'])->name('admin.categories.edit');
            Route::put('/{slug}', [CategoryController::class, 'update'])->name('admin.categories.update');
        
            // Delete category (soft delete)
            Route::delete('/{slug}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');
        
            // Soft delete management
            
            Route::post('/{slug}/restore', [CategoryController::class, 'restore'])->name('admin.categories.restore');
            Route::delete('/{slug}/force-delete', [CategoryController::class, 'forceDelete'])->name('admin.categories.force-delete');
        
            // Utility routes
            Route::post('/generate-slug', [CategoryController::class, 'generateSlug'])->name('admin.generate-slug');
            Route::post('/check-unique', [CategoryController::class, 'checkUnique'])->name('admin.categories.check-unique');
            Route::post('/edit/check-unique', [CategoryController::class, 'checkUniqueForEdit'])->name('admin.categories.edit.check-unique');
        });
        // Products Routes
        Route::prefix('/products')->group(function () {
            Route::get('/', [ProductController::class, 'index'])->name('admin.products');
            Route::get('/create', [ProductController::class, 'create'])->name('admin.products.create');
            Route::post('/check-unique', [ProductController::class, 'checkUnique'])->name('admin.products.check-unique');
            Route::post('/check-edit-unique', [ProductController::class, 'checkEditUnique'])->name('admin.products.check-edit-unique');
            Route::post('/store', [ProductController::class, 'store'])->name('admin.products.store');
            Route::delete('/destroy', [ProductController::class, 'destroy'])->name('admin.products.destroy');
            Route::get('/{product:slug}/edit', [ProductController::class, 'edit'])->name('admin.products.edit');
            Route::put('/update', [ProductController::class, 'update']);
        });
    });

    Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
});