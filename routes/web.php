<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [UserController::class, 'index']);

// Route::get('/', function(){
//     return Inertia::render('Pages/Auth/login');
// });

//user add to cart

Route::prefix('cart')->controller(CartController::class)->group(function(){
    route::get('view', 'view')->name('cart.view');
    route::post('store /{product}', 'store')->name('cart.store');
    route::patch('update /{product}', 'update')->name('cart.update');
    route::delete('delete /{product}', 'destroy')->name('cart.delete');
});

Route::get('/dashboard', function () {
return Inertia::render('Dashboard');
})->middleware(['auth', 'verified', 'redirectAdmin'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//Admin

Route::group(['prefix' =>'admin', 'middleware' => 'redirectAdmin'], function () {
    Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login', [AdminAuthController::class, 'login'])->name('admin.login.post');
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    Route::delete('/products/image/{id}', [ProductController::class, 'deleteImage'])->name('admin.product.image.delete');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function (){
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/products', [ProductController::class, 'index'])->name('admin.products.index');
    Route::post('product/store', [ProductController::class, 'store'])->name('admin.product.store');
    Route::put('products/update/{id}', [ProductController::class, 'update'])->name('admin.product.update');
    Route::delete('products/destroy/{id}', [ProductController::class, 'destroy'])->name('admin.product.delete');
});
require __DIR__.'/auth.php';
