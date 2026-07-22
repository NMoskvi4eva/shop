<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    /**
     * Створення замовлення та генерація форми LiqPay
     */
    public function checkout(Request $request)
    {
        // 1. Валідація: перевіряємо, що ім'я та телефон обов'язково передані
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ]);

        $totalAmount = 0;
        $productsItems = [];

        // 🎯 ЧИТАЄМО ДАНІ КОШИКА З ФОРМИ (cart_data), ЯКІ ПЕРЕДАЄ ВАШ САЙТ
        if ($request->has('cart_data') && !empty($request->input('cart_data'))) {
            // Розкодовуємо JSON-рядок товарів у PHP-масив
            $cart = json_decode($request->input('cart_data'), true);

            if (is_array($cart)) {
                foreach ($cart as $item) {
                    // Рахуємо реальну суму (ціна * кількість)
                    $totalAmount += ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
                    
                    // Збираємо назви десертів для текстового поля
                    $productsItems[] = ($item['name'] ?? 'Десерт') . ' (' . ($item['quantity'] ?? 1) . ' шт)';
                }
            }
        }

        // Якщо після обробки форми кошик виявився порожнім, повертаємо назад
        if (empty($productsItems) || $totalAmount == 0) {
            return redirect()->back()->with('error', 'Помилка: ваш кошик порожній або дані передані некоректно.');
        }

        // Об'єднуємо назви товарів у єдиний рядок для бази даних
        $productsListString = implode(', ', $productsItems);

        // Генеруємо унікальний ID замовлення для LiqPay
        $liqpayOrderId = 'order_' . time() . '_' . rand(100, 999);

        // 2. Зберігаємо замовлення в базу, фіксуючи повністю РЕАЛЬНІ ДАНІ
        $order = Order::create([
            'name'            => $request->input('name'),
            'phone'           => $request->input('phone'),
            'total_amount'    => $totalAmount,         // Точна сума замовлення з кошика
            'status'          => 'Очікує оплату',       // Початковий статус для адмінки
            'liqpay_order_id' => $liqpayOrderId,
            'products_list'   => $productsListString,  // Перелік реально куплених десертів
        ]);

        // 3. Беремо ключі безпечно з файлу .env
        $publicKey = env('LIQPAY_PUBLIC_KEY');
        $privateKey = env('LIQPAY_PRIVATE_KEY');

        // 4. Формуємо масив параметрів платіжної сторінки LiqPay
        $liqpayParams = [
        'public_key'  => $publicKey,
        'version'     => '3',
        'action'      => 'pay',
        'amount'      => $order->total_amount,
        'currency'    => 'UAH',
        'description' => 'Оплата замовлення #' . $order->id,
        'order_id'    => $liqpayOrderId,
        'sandbox'     => '1',
        'result_url'  => url('/payment-callback'),
        'server_url'  => url('/liqpay-webhook'),
    ];

    
        // 5. Кодуємо параметри в Base64 за правилами LiqPay
        $data = base64_encode(json_encode($liqpayParams));

        // 6. Створюємо правильну SHA-1 сигнатуру
        $signature = base64_encode(sha1($privateKey . $data . $privateKey, true));

        // 7. Передаємо сформовані змінні у Blade-представлення редіректу
        return view('checkout.redirect', compact('data', 'signature'));
    }

    /**
     * Фоновий Webhook від LiqPay (обробка успішного платежу)
     */
   public function webhook(Request $request)
{
    $privateKey = env('LIQPAY_PRIVATE_KEY');

    $data = $request->input('data');
    $signature = $request->input('signature');

    $parsedSignature = base64_encode(
        sha1($privateKey . $data . $privateKey, true)
    );

    if ($signature !== $parsedSignature) {
        dd('Signature error');
    }

    $response = json_decode(base64_decode($data));

    dd($response);
}

    /**
     * Сторінка успішного повернення клієнта
     */
    public function callback()
    {
        return view('checkout.success');
    }
}