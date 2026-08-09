@extends('layouts.admin')

@section('page_title', 'Керування товарами')

@section('content')
<style>
    .admin-container {
        display: flex;
        gap: 2rem;
        align-items: flex-start;
    }

    /* 📝 Стилі для картки форми (Ліворуч) */
    .form-card {
        background: #fff;
        border-radius: 16px;
        padding: 2rem;
        width: 380px;
        box-shadow: 0 4px 20px rgba(74, 59, 57, 0.03);
        border: 1px solid var(--border-color);
        position: sticky;
        top: 0;
    }

    .form-card h3 {
        font-size: 1.1rem;
        margin-bottom: 1.5rem;
        color: var(--text-main);
        font-weight: 600;
    }

    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-group label {
        display: block;
        font-size: 0.8rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #887876;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        font-size: 0.85rem;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        outline: none;
        font-family: inherit;
        color: var(--text-main);
        transition: border-color 0.3s;
    }

    .form-control:focus {
        border-color: var(--pink-accent);
    }

    textarea.form-control {
        resize: none;
        height: 80px;
    }

    .btn-submit {
        width: 100%;
        padding: 0.85rem;
        background-color: var(--pink-accent);
        color: #fff;
        border: none;
        border-radius: 25px;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.3s;
        margin-top: 0.5rem;
    }

    .btn-submit:hover {
        background-color: #df7a6c;
    }

    /* 📊 Стилі для таблиці товарів (Праворуч) */
    .table-container {
        flex-grow: 1;
        background: #fff;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 20px rgba(74, 59, 57, 0.03);
        border: 1px solid var(--border-color);
        overflow: hidden;
    }

    .admin-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
        font-size: 0.85rem;
    }

    .admin-table th {
        padding: 1rem;
        font-weight: 600;
        color: #887876;
        border-bottom: 2px solid var(--pink-bg);
    }

    .admin-table td {
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid var(--pink-bg);
    }

    .product-img {
        width: 45px;
        height: 45px;
        object-fit: cover;
        border-radius: 8px;
    }

    .badge-category {
        background-color: var(--pink-bg);
        color: var(--pink-accent);
        padding: 0.25rem 0.6rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .btn-delete {
        background: none;
        border: none;
        color: #dc3545;
        font-weight: 600;
        font-size: 0.8rem;
        cursor: pointer;
        font-family: inherit;
        transition: color 0.2s;
    }

    .btn-delete:hover {
        color: #bd2130;
    }

    /* Сповіщення про успіх */
    .alert-success {
        background-color: #e2f0d9;
        color: #385723;
        padding: 0.75rem 1.2rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        font-size: 0.85rem;
        font-weight: 500;
    }
</style>

{{-- Виведення повідомлень про успішне додавання/видалення --}}
@if(session('success'))
    <div class="alert-success">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div style="background-color: #f8d7da; color: #721c24; padding: 0.75rem 1.2rem; border-radius: 8px; margin-bottom: 1.5rem; font-size: 0.85rem;">
        <ul style="margin: 0; padding-left: 1rem;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="admin-container">
    
    <div class="form-card">
        <h3>Додати десерт</h3>
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-group">
                <label>Категорія *</label>
                <select name="category_id" class="form-control" required>
                    <option value="" disabled selected>— Оберіть категорію —</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Назва десерту *</label>
                <input type="text" name="name" class="form-control" placeholder="Наприклад, Макарун Полуничний" required>
            </div>

            <div class="form-group">
                <label>Опис десерту</label>
                <textarea name="description" class="form-control" placeholder="Ніжний крем, французька рецептура..." required></textarea>
            </div>

            <div class="form-group">
                <label>Ціна (грн) *</label>
                <input type="number" step="0.01" name="price" class="form-control" placeholder="85.00" required>
            </div>

            <div class="form-group">
                <label>Зображення десерту *</label>
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>

            <button type="submit" class="btn-submit">Опублікувати на сайті</button>
        </form>
    </div>

    <div class="table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Фото</th>
                    <th>Назва</th>
                    <th>Категорія</th>
                    <th>Ціна</th>
                    <th>Дія</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td>
                            <img src="{{ asset($product->image) }}" class="product-img" alt="{{ $product->name }}">
                        </td>
                        <td style="font-weight: 500;">{{ $product->name }}</td>
                        <td>
                            <span class="badge-category">{{ $product->category->name ?? 'Без категорії' }}</span>
                        </td>
                        <td style="font-weight: 600;">{{ number_format($product->price, 2, '.', '') }} грн</td>
                        <td>
                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Ви впевнені, що хочете видалити цей товар?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete">Видалити</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: #887876; padding: 2rem;">
                            У меню ще немає жодного десерту.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection