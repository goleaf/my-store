<?php

use App\Livewire\BrandPage;
use App\Livewire\Account\Addresses;
use App\Livewire\Account\Notifications;
use App\Livewire\Account\Orders;
use App\Livewire\Account\OrderDetails;
use App\Livewire\Account\PaymentMethods;
use App\Livewire\Account\Settings;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\CartPage;
use App\Livewire\CheckoutPage;
use App\Livewire\CheckoutSuccessPage;
use App\Livewire\CollectionPage;
use App\Livewire\Home;
use App\Livewire\ProductPage;
use App\Livewire\SearchPage;
use App\Livewire\ShopGrid;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', Home::class)->name('home');

Route::get('shop', ShopGrid::class)->name('shop.view');

Route::middleware('guest')->group(function () {
    Route::get('login', Login::class)->name('login');
    Route::get('register', Register::class)->name('register');
    Route::get('forgot-password', ForgotPassword::class)->name('password.request');
    Route::get('reset-password/{token}', ResetPassword::class)->name('password.reset');
});

Route::post('logout', function () {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();

    return redirect('/');
})->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('account/settings', Settings::class)->name('account.settings');
    Route::get('account/orders', Orders::class)->name('account.orders');
    Route::get('account/orders/{order}', OrderDetails::class)->name('account.orders.view');
    Route::get('account/addresses', Addresses::class)->name('account.addresses');
    Route::get('account/payment-methods', PaymentMethods::class)->name('account.payment-methods');
    Route::get('account/notifications', Notifications::class)->name('account.notifications');
});

Route::get('/collections/{slug}', CollectionPage::class)->name('collection.view');

Route::get('/products/{slug}', ProductPage::class)->name('product.view');

Route::get('search', SearchPage::class)->name('search.view');

Route::get('cart', CartPage::class)->name('cart.view');

Route::get('brands/{slug}', BrandPage::class)->name('brand.view');

Route::get('checkout', CheckoutPage::class)->name('checkout.view');

Route::get('checkout/success', CheckoutSuccessPage::class)->name('checkout-success.view');
