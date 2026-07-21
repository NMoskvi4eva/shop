<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Дозволяємо масове заповнення поля назви категорії
    protected $fillable = ['name'];

    // Зв'язок: одна категорія має багато товарів
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}