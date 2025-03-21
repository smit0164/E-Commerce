<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\CustomerAuthController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Customer\HomeController;
use App\Http\Controllers\Customer\ProductController as CustomerProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/category/{slug}', [HomeController::class, 'show'])->name('category.products');
Route::get('/products', [CustomerProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [CustomerProductController::class, 'show'])->name('products.show');
Route::get('/cart', fn() => view('pages.products.cart'))->name('cart');
Route::get('/checkout', fn() => view('pages.products.checkout'))->name('checkout');
Route::get('/contact', fn() => view('pages.products.contact'))->name('contact');



Route::middleware("guest:customer")->group(function () {
    Route::get('register', [CustomerAuthController::class, 'showRegisterForm'])->name('register');
    Route::post('register', [CustomerAuthController::class, 'register']);
    Route::get('login', [CustomerAuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [CustomerAuthController::class, 'login']);
});
Route::middleware("auth:customer")->group(function () {
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/add', [CartController::class, 'addCart'])->name('add');
        Route::post('/update', [CartController::class, 'updateCart'])->name('update');
        Route::post('/remove', [CartController::class, 'removeFromCart'])->name('remove');
    });
    
    Route::prefix('checkout')->name('checkout.')->group(function () {
        Route::get('/', [CheckoutController::class, 'index'])->name('index');
        Route::post('/', [CheckoutController::class, 'store'])->name('store');
    });
    
    Route::post('logout', [CustomerAuthController::class, 'logout'])->name('logout');
});


Route::middleware('guest:admin')->group(function () {
    Route::prefix('admin')->group(function () {
        Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
        Route::post('login', [AdminAuthController::class, 'login']);
    });
});
Route::middleware('auth:admin')->group(function () {
    Route::prefix('admin')->group(function () {
        Route::view('/dashboard', 'pages.admin.dashboard')->name('admin.dashboard');
        Route::view('/orders', 'pages.admin.orders')->name('admin.orders');
        Route::view('/users', 'pages.admin.users')->name('admin.users');

        Route::prefix('/categories')->group(function () {
            Route::get('/', [CategoryController::class, 'showCategory'])->name('admin.categories');
            Route::post('/generate-slug', [CategoryController::class, 'generateSlug'])->name('admin.categories.generate-slug');
            Route::post('/store', [CategoryController::class, 'store'])->name('admin.categories.store');
            Route::post('/check-unique', [CategoryController::class, 'checkUnique'])->name('admin.categories.check-unique');
            Route::post('/edit/check-unique', [CategoryController::class, 'checkUniqueForEdit']);
            Route::delete('/delete', [CategoryController::class, 'destroy'])->name('admin.categories.delete');
            Route::put('/update', [CategoryController::class, 'update']);
        });
        Route::prefix('/products')->group(function () {
            Route::get('/', [ProductController::class, 'index'])->name('admin.products');
            Route::get('/products/create', [ProductController::class, 'create'])->name('admin.products.create');
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
