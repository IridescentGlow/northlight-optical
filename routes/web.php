<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ConfirmationController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UpdateAccountController;
use App\Http\Controllers\UpdatePasswordController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\PromiseController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\IsCartEmpty;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', HomeController::class)->name('home');


Route::resource('products', ProductController::class)->only(['index', 'show']);

Route::resource('cart', CartController::class);

Route::get('about', AboutController::class)->name('about');
Route::get('services', ServicesController::class)->name('services');
Route::get('promise', PromiseController::class)->name('promise');
Route::get('testimonials', TestimonialController::class)->name('testimonials');

Route::get('contact', [ContactController::class, 'create'])->name('contact.create');
Route::post('contact', [ContactController::class, 'store'])->name('contact.store');

Route::get('register', [RegisterController::class, 'create'])->name('register.create');
Route::post('register', [RegisterController::class, 'store'])->name('register.store');


Route::get('login', [LoginController::class, 'create'])->name('login.create');
Route::post('login', [LoginController::class, 'store'])->name('login.store');


Route::middleware('auth')->group(function() {

    Route::match(['get', 'post'], 'logout', LogoutController::class)->name('logout');

    Route::get('account', AccountController::class)->name('account.show');

    Route::get('orders', OrderController::class)->name('orders.index');

    Route::get('update-account', [UpdateAccountController::class, 'create'])->name('update.account.create');
    Route::post('update-account', [UpdateAccountController::class, 'store'])->name('update.account.store');
    Route::delete('update-account', [UpdateAccountController::class, 'destroy'])->name('update.account.destroy');

    Route::get('update-password', [UpdatePasswordController::class, 'create'])->name('update.password.create');
    Route::post('update-password', [UpdatePasswordController::class, 'store'])->name('update.password.store');


});


Route::middleware('auth', IsCartEmpty::class)->group(function() {
    
    Route::get('checkout', [CheckoutController::class, 'create'])->name('checkout.create');
    Route::post('checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    Route::get('confirmation', ConfirmationController::class)->name('confirmation');

});


