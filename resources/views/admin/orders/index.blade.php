@extends('layouts.admin')

@section('page_title', 'Замовлення покупців')

@section('content')
<style>
    .orders-card {
        background: #fff;
        border-radius: 16px;
        padding: 2.5rem;
        box-shadow: 0 4px 20px rgba(74, 59, 57, 0.03);
        border: 1px solid var(--border-color);
        width: 100%;
        max-width: 1100px;
    }

    .orders-card h3 {
        font-size: 1.2rem;
        margin-bottom: 1.5rem;
        color: var(--text-main);
        font-weight: 600;
    }

    /* Стилі для адаптивної таблиці замовлень */
    .admin-table-wrapper {
        overflow-x: auto;
        margin-top: 1rem;
    }

    .admin-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
        font-size: 0.9rem;
    }

    .admin-table th {
        background-color: var(--pink-bg);
        color: var(--text-main);
        padding: 1rem;
        font-weight: 600;
        border-bottom: 2px solid var(--border-color);
    }

    .admin-table td {
        padding: 1rem;
        border-bottom: 1px solid var(--border-color);
        color: var(--text-main);
        vertical-align: middle;
    }

    .admin-table tr:hover {
        background-color: rgba(233, 139, 125, 0.02);
    }

    /* Стилізація випадаючого списку статусів */
    .status-select {
        padding: 6px 10px;
        border-radius: 6px;
        border: 1px solid var(--border-color);
        font-family: 'Montserrat', sans-serif;
        background-color: #fff;
        color: var(--text-main);
        font-size: 0.85rem;
        font-weight: 500;
        cursor: pointer;
        outline: none;
        transition: border-color 0.2s;
    }

    .status-select:focus {
        border-color: var(--pink-accent);
    }

    .alert-success {
        background-color: #e6f7ed;
        color: #1e7e34;
        padding: 0.75rem 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        font-size: 0.85rem;
        font-weight: 500;
        border: 1px solid #d4edda;
    }

    .no-orders {
        text-align: center;
        color: #887876;
        padding: 2rem 0;
        font-size: 0.9rem;
    }
</style>

<div class="orders-card">
    <h3>Список замовлень з LiqPay</h3>

    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="admin-table-wrapper">
        @if($orders->isEmpty())
            <p class="no-orders">Наразі замовлень у системі немає.</p>
        @else
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Покупець</th>
                        <th>Телефон</th>
                        <th>Сума</th>
                        <th>Статус замовлення</th>
                        <th>Дата створення</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td><strong>#{{ $order->id }}</strong></td>
                            <td>{{ $order->name ?? 'Гість' }}</td>
                            <td>{{ $order->phone ?? '—' }}</td>
                            <td><strong>{{ number_format($order->total_amount ?? 0, 2, '.', ' ') }} грн</strong></td>
                            <td>
                                <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    
                                    <select name="status" class="status-select" onchange="this.form.submit()">
                                        <option value="Очікує оплату" {{ $order->status == 'Очікує оплату' ? 'selected' : '' }}>⏳ Очікує оплату</option>
                                        <option value="Оплачено" {{ $order->status == 'Оплачено' ? 'selected' : '' }}>✅ Оплачено</option>
                                        <option value="Готується" {{ $order->status == 'Готується' ? 'selected' : '' }}>👩‍🍳 Готується</option>
                                        <option value="Доставлено" {{ $order->status == 'Доставлено' ? 'selected' : '' }}>📦 Доставлено</option>
                                        <option value="Скасовано" {{ $order->status == 'Скасовано' ? 'selected' : '' }}>❌ Скасовано</option>
                                    </select>
                                </form>
                            </td>
                            <td>{{ $order->created_at ? $order->created_at->format('d.m.Y H:i') : '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection