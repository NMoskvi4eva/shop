<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\Category;
use App\Models\AuditLog; // 🎯 Імпортуємо модель для логування подій

class ProductController extends Controller
{
   /**
     * Відображення списку товарів та категорій в адмінці
     */
    public function index()
    {
        // 🎯 Сортуємо товари так, щоб найновіші (останні додані) були вгорі сторінки
        $products = Product::orderBy('created_at', 'desc')->get(); 
        
        $categories = Category::all();
        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * Створення нового товару (з автоматичним логуванням)
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'image'       => 'nullable|string',
        ]);

        // Створюємо продукт
        $product = Product::create($request->all());

        // 📝 ЗАПИС У ЖУРНАЛ АУДИТУ
        AuditLog::create([
            'user_email' => auth()->user()->email ?? 'admin@gmail.com',
            'action'     => 'Додавання товару',
            'details'    => "Додано новий десерт: {$product->name} (Ціна: {$product->price} грн)"
        ]);

        return redirect()->route('admin.products.index');
    }

    /**
     * Видалення товару (з автоматичним логуванням)
     */
    public function destroy(Product $product)
    {
        // 📝 ЗАПИС У ЖУРНАЛ АУДИТУ (фіксуємо назву перед видаленням)
        AuditLog::create([
            'user_email' => auth()->user()->email ?? 'admin@gmail.com',
            'action'     => 'Видалення товару',
            'details'    => "Видалено десерт: {$product->name}"
        ]);

        $product->delete();
        return redirect()->route('admin.products.index');
    }

    /**
     * Відображення списку замовлень покупців
     */
    public function ordersIndex()
    {
        $orders = Order::orderBy('created_at', 'desc')->get();
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Оновлення статусу замовлення (з валідацією та логуванням)
     */
    public function updateOrderStatus(Request $request, Order $order)
    {
        // Валідуємо, щоб статус відповідав лише дозволеним українським значенням
        $request->validate([
            'status' => 'required|string|in:Очікує оплату,Оплачено,Готується,Доставлено,Скасовано'
        ]);

        $oldStatus = $order->status;
        $order->update(['status' => $request->status]);

        // 📝 ЗАПИС У ЖУРНАЛ АУДИТУ
        AuditLog::create([
            'user_email' => auth()->user()->email ?? 'admin@gmail.com',
            'action'     => 'Зміна статусу замовлення',
            'details'    => "Змінено статус замовлення #{$order->id} з '{$oldStatus}' на '{$request->status}'"
        ]);

        return back()->with('success', 'Статус замовлення успішно оновлено!');
    }

    /**
     * Відображення журналу дій системи (Аудит) в адмінці
     */
    public function logsIndex()
    {
        $logs = AuditLog::orderBy('created_at', 'desc')->get();
        return view('admin.logs.index', compact('logs'));
    }
}