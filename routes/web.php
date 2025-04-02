<?php
use App\Http\Controllers\Auth\CustomerAuthController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Customer\HomeController;
use App\Http\Controllers\Customer\StaticPagePublicController;
use App\Http\Controllers\Customer\ProductController as CustomerProductController;
use Illuminate\Support\Facades\Route;

// Customer Routes

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/category/{slug}', [HomeController::class, 'show'])->name('category.products');
Route::prefix('products')->name('products.')->group(function () {
    Route::get('/filter', [CustomerProductController::class, 'index'])->name('filter');
    Route::get('/', [CustomerProductController::class, 'index'])->name('index');
    Route::get('/{slug}', [CustomerProductController::class, 'show'])->name('show');
});
Route::get('/page/{slug}', [StaticPagePublicController::class, 'show'])->name('static.page');



Route::get('/search', [CustomerProductController::class, 'search'])->name('products.search');

Route::middleware("guest:customer")->group(function () {
    Route::get('register', [CustomerAuthController::class, 'showRegisterForm'])->name('register');
    Route::post('register', [CustomerAuthController::class, 'register']);
    Route::get('login', [CustomerAuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [CustomerAuthController::class, 'login']);
});

Route::middleware("auth:customer")->group(function () {
    Route::get('/customer/profile',[HomeController::class,'customerProfile'])->name('customer.profile');
    Route::put('/customer/profile',[HomeController::class,'updateCustomerProfile'])->name('customer.profile.update');
    Route::post('/customer/fetch/address',[HomeController::Class,'fetchCustomerAddress'])->name('customer.address.fetch');
    Route::put('/customer/edit/address',[HomeController::Class,'editCustomerAddress'])->name('customer.address.edit');

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
    Route::get('/order-success', function () {
        return view('pages.customer.products.order-success', ['order' => session('order')]);
    })->name('order.success');
    Route::get('/order/{id}',[CheckoutController::class, 'showOrdersDetails'])->name('show.oreder.details');
    Route::get('/showorder',[HomeController::class,'showOrderHistory'])->name('show.order.history');
    Route::get('/customer/orders/{orderId}/details', [HomeController::class, 'getOrderDetails'])->name('customer.order.details');
    Route::post('logout', [CustomerAuthController::class, 'logout'])->name('logout');
});

// Include Admin Routes
require __DIR__ . '/admin.php';