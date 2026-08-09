<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\Category;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Storage; // 🎯 Імпортуємо фасад для роботи з файлами

class ProductController extends Controller
{
   /**
     * Відображення списку товарів та категорій в адмінці
     */
    public function index()
    {
        $products = Product::orderBy('created_at', 'desc')->get(); 
        $categories = Category::all();
        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * Створення нового товару (з автоматичним логуванням та збереженням картинки)
     */
    public function store(Request $request)
    {
        // 🎯 ВИПРАВЛЕНО: Змінено 'string' на правила валідації файлу картинки
        $validatedData = $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120', // до 5 МБ
        ]);

        // 🎯 ОБРОБКА ФАЙЛУ: Зберігаємо картинку на диск і отримуємо шлях
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validatedData['image'] = 'storage/' . $path; // Рядок шляху для БД
        }

        // Створюємо продукт із завантаженим шляхом до зображення
        $product = Product::create($validatedData);

        // 📝 ЗАПИС У ЖУРНАЛ АУДИТУ
        AuditLog::create([
            'user_email' => auth()->user()->email ?? 'admin@gmail.com',
            'action'     => 'Додавання товару',
            'details'    => "Додано новий десерт: {$product->name} (Ціна: {$product->price} грн)"
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Десерт успішно додано!');
    }

    /**
     * Видалення товару (з очищенням картинки з диска)
     */
    public function destroy(Product $product)
    {
        // 🎯 ВИДАЛЕННЯ ФАЙЛУ: Видаляємо фізичну картинку з диска
        if ($product->image) {
            $relativePath = str_replace('storage/', '', $product->image);
            Storage::disk('public')->delete($relativePath);
        }

        // 📝 ЗАПИС У ЖУРНАЛ АУДИТУ
        AuditLog::create([
            'user_email' => auth()->user()->email ?? 'admin@gmail.com',
            'action'     => 'Видалення товару',
            'details'    => "Видалено десерт: {$product->name}"
        ]);

        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Товар успішно видалено!');
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
     * Оновлення статусу замовлення
     */
    public function updateOrderStatus(Request $request, Order $order)
    {
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
     * Відображення журналу дій системи (Аудит)
     */
    public function logsIndex()
    {
        $logs = AuditLog::orderBy('created_at', 'desc')->get();
        return view('admin.logs.index', compact('logs'));
    }
}