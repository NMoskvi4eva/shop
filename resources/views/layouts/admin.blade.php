<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pâtisserie Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --dark-brown: #331d1a;
            --pink-accent: #e98b7d;
            --pink-bg: #fff5f3;
            --text-main: #4a3b39;
            --border-color: #f3dcd8;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Montserrat', sans-serif;
        }

        body {
            display: flex;
            background-color: var(--pink-bg);
            color: var(--text-main);
            height: 100vh;
            overflow: hidden;
        }

        /* Ліва темна панель */
        .sidebar {
            width: 260px;
            background-color: var(--dark-brown);
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 2rem 1.5rem;
        }

        .logo {
            font-size: 1.25rem;
            font-weight: 700;
            letter-spacing: 1px;
            margin-bottom: 2.5rem;
            text-transform: uppercase;
        }

        .menu-items {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            flex-grow: 1;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 0.85rem 1rem;
            color: #d1c7c5;
            text-decoration: none;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.3s;
        }

        .menu-item:hover, .menu-item.active {
            background-color: rgba(255, 255, 255, 0.08);
            color: #fff;
        }

        .logout-btn {
            color: #c47569;
            text-decoration: none;
            font-size: 0.85rem;
            padding: 0.5rem 1rem;
            transition: color 0.3s;
            cursor: pointer;
            background: none;
            border: none;
            text-align: left;
            width: 100%;
            font-weight: 500;
        }

        .logout-btn:hover {
            color: #e98b7d;
        }

        /* Права головна частина */
        .main-content {
            flex-grow: 1;
            padding: 2.5rem;
            overflow-y: auto;
        }

        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .admin-header h2 {
            font-size: 1.6rem;
            font-weight: 600;
        }

        .admin-user {
            font-size: 0.85rem;
            color: #887876;
            font-weight: 500;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <div>
            <div class="logo">Pâtisserie Admin</div>
            <div class="menu-items">
                <a href="{{ route('home') }}" class="menu-item">Головна</a>                
                <a href="{{ route('admin.products.index') }}" class="menu-item {{ Request::is('admin/products*') ? 'active' : '' }}">Керування товарами</a>
                
                <a href="{{ route('admin.orders.index') }}" class="menu-item {{ Request::is('admin/orders*') ? 'active' : '' }}">Замовлення</a>
                <a href="{{ route('admin.logs.index') }}" class="menu-item {{ request()->is('admin/logs') ? 'active' : '' }}">Журнал аудиту</a>
            </div>
        </div>
        
        <a href="#" class="logout-btn" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            Вихід з акаунту
        </a>
        
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>

    <div class="main-content">
        <div class="admin-header">
            <h2>@yield('page_title')</h2>
            
            <div class="admin-user">
                Вітаємо, {{ Auth::check() ? Auth::user()->name : 'Admin' }}
            </div>
        </div>

        @yield('content')
    </div>

</body>
</html>