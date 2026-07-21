<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Головна сторінка сайту (вітрина для покупців)
     */
    public function index(Request $request)
    {
        // 1. Отримуємо категорії для меню
        $categories = Category::all();

        // 2. Будуємо запит до товарів
        $query = Product::query();

        // 3. Фільтруємо за категорією, якщо обрано
        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }

        // 4. Сортуємо десерти: нові будуть першими
        $products = $query->orderBy('id', 'desc')->get();

        // 5. Повертаємо головний шаблон сайту
        return view('welcome', compact('products', 'categories'));
    }
}