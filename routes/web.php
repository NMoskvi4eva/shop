<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController; 
use App\Http\Controllers\Admin\ProductController as AdminProductController; 
use App\Http\Controllers\OrderController; 
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\DB;
/* <test> */

Route::get('/test-db', function () {
    try {
        DB::connection()->getPdo();
        return DB::select("SHOW TABLES");
    } catch (\Exception $e) {
        return $e->getMessage();
    }
});
/*
|--------------------------------------------------------------------------
| АВТЕНТИФІКАЦІЯ (Вхід, Реєстрація, Вихід)
|--------------------------------------------------------------------------
*/
// Маршрути для реєстрації
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Маршрути для входу
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Вихід із системи
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| КЛІЄНТСЬКА ЧАСТИНА (Для покупців)
|--------------------------------------------------------------------------
*/
// Головна сторінка сайту (вітрина з десертами)
Route::get('/', [ProductController::class, 'index'])->name('home');

// Оформлення замовлення (форма відправляє запит сюди)
Route::post('/checkout', [OrderController::class, 'checkout'])->name('checkout.store');

// Сторінка, куди LiqPay повертає покупця після успішної оплати
Route::get('/payment-callback', [OrderController::class, 'callback'])->name('payment.callback');

// Фоновий Webhook від LiqPay (сповіщення сайту про статус платежу)
Route::post('/liqpay-webhook', [OrderController::class, 'webhook'])->name('liqpay.webhook');


/*
|--------------------------------------------------------------------------
| АДМІНІСТРАТИВНА ЧАСТИНА (З розмежуванням доступу через Middleware)
|--------------------------------------------------------------------------
*/
// Блок захищено посередниками: користувач має бути залогінений (auth) та мати права адміна (admin)
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    
    // Головний маршрут адмінки
    Route::get('/', function () {
        return redirect()->route('admin.products.index');
    })->name('admin.index');

    // Керування товарами
    Route::get('/products', [AdminProductController::class, 'index'])->name('admin.products.index');
    Route::post('/products', [AdminProductController::class, 'store'])->name('admin.products.store');
    Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])->name('admin.products.destroy');

    // Керування замовленнями
    Route::get('/orders', [AdminProductController::class, 'ordersIndex'])->name('admin.orders.index');
    
    // Оновлення статусу замовлення
    Route::patch('/orders/{order}/status', [AdminProductController::class, 'updateOrderStatus'])->name('admin.orders.updateStatus');

    // 🎯 ЖУРНАЛ ДІЙ СИСТЕМИ (Аудит)
    Route::get('/logs', [AdminProductController::class, 'logsIndex'])->name('admin.logs.index');
}); 

