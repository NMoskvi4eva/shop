<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pâtisserie — Смак Вашого Щастя</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&family=Playfair+Display:ital,wght=0,500;0,600;1,400&display=swap" rel="stylesheet">
    
   <link rel="stylesheet" href="{{ asset('assets/style.css') }}">
</head>
<body>

<header class="header">
    <div class="logo">P<span>â</span>tisserie</div>
    <nav>
        <ul class="nav-links">
            <li><a href="{{ url('/') }}">Головна</a></li>
            <li><a href="#menu">Авторські шедеври</a></li>
            <li><a href="#contacts">Контакти</a></li>
        </ul>
    </nav>
    <div class="header-icons">
        @auth
            @if(auth()->user()->role === 'admin')
                <a href="{{ url('/admin') }}" class="icon-btn" title="Панель адміна" style="text-decoration: none; margin-right: 0.5rem; display: inline-flex; align-items: center;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="9" y1="3" x2="9" y2="21"></line><line x1="15" y1="12" x2="15" y2="12"></line></svg>
                </a>
            @endif
            
            <form action="{{ url('/logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="icon-btn" title="Вийти з акаунта" style="background:none; border:none; color: var(--accent-coral); font-weight: 600; font-size: 0.8125rem; margin-right: 0.5rem; display: inline-flex; align-items: center; gap: 0.25rem; cursor:pointer;">
                    🚪 <span style="text-transform: uppercase; letter-spacing: 0.0625rem;">Вихід</span>
                </button>
            </form>
        @else
            <a href="{{ url('/login') }}" class="icon-btn" title="Особистій кабінет" style="text-decoration: none; display: inline-flex; align-items: center;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            </a>
        @endauth

        <button id="cart-btn" class="icon-btn" title="Кошик">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
            <span class="cart-badge" id="cart-badge-count">0</span>
        </button>
    </div>
</header>

<section class="hero">
    <div class="hero-text">
        <span class="hero-badge">Кожен десерт — шедевр мистецтва</span>
        <h1>Смак Вашого<br><span>Щастя</span></h1>
        <p>Доторкніться до витонченої естетики смаку. Ми вручну створюємо неперевершені десерти з преміальних натуральних інгредієнтів для ваших найцінніших митей.</p>
        <div class="hero-buttons">
            <a href="#menu" class="btn btn-primary">Замовити зараз</a>
            <a href="#menu" class="btn btn-outline">Переглянути меню</a>
        </div>
    </div>
    <div class="hero-images">
        <div class="img-large" style="background-image: url('https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=600');">
            <div class="img-label">Торт "Шоколадний Нео-Класик"</div>
        </div>
        <div class="img-small" style="background-image: url('https://images.unsplash.com/photo-1569864358642-9d1684040f43?w=300');"></div>
        <div class="img-small" style="background-image: url('https://images.unsplash.com/photo-1551024601-bec78aea704b?w=300');"></div>
    </div>
</section>

<main class="menu-section" id="menu">
    <p class="menu-subtitle">Вишукане меню</p>
    <h2 class="menu-title">Авторські шедеври</h2>
    
    <div class="controls-container" style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 1rem; margin-bottom: 2rem; max-width: 1200px; margin-left: auto; margin-right: auto; padding: 0 1rem;">
        
        <div class="filter-container" style="display: flex; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 0;">
            <a href="{{ route('home', ['sort' => request('sort')]) }}#menu" 
               class="filter-btn {{ !request('category_id') ? 'active' : '' }}" 
               style="text-decoration: none;">
               Усі шедеври ({{ \App\Models\Product::count() }})
            </a>
            @foreach ($categories as $category)
                <a href="{{ route('home', ['category_id' => $category->id, 'sort' => request('sort')]) }}#menu" 
                   class="filter-btn {{ request('category_id') == $category->id ? 'active' : '' }}" 
                   style="text-decoration: none;">
                   {{ $category->name }}
                </a>
            @endforeach
        </div>

        <div class="sort-container">
    <div class="custom-dropdown">
        <button class="dropdown-trigger" id="sort-trigger-text">
            Сортування за замовчуванням
        </button>
        <div class="dropdown-menu" id="sort-dropdown-menu">
            <a href="#" data-sort="default" class="selected">Сортування за замовчуванням</a>
            <a href="#" data-sort="price_asc">Від дешевих до дорогих</a>
            <a href="#" data-sort="price_desc">Від дорогих до дешевих</a>
            <a href="#" data-sort="name_asc">За алфавітом (А-Я)</a>
            <a href="#" data-sort="name_desc">За алфавітом (Я-А)</a>
        </div>
    </div>


    </div>
    
    <div class="products-grid">
        @forelse ($products as $product)
            <div class="product-card" data-category="{{ $product->category_id }}">
                <div class="product-image-placeholder" style="background-image: url('{{ asset($product->image) }}');"></div>
                <h3>{{ $product->name }}</h3>
                <p>{{ $product->description }}</p>
                <div class="product-footer">
                    <div class="price">{{ number_format($product->price, 2, '.', '') }} грн</div>
                    <button class="btn-card-buy" 
                        data-id="{{ $product->id }}" 
                        data-name="{{ $product->name }}" 
                        data-price="{{ $product->price }}">
                        в кошик
                    </button>
                </div>
            </div>
        @empty
            <p style="grid-column: 1/-1; text-align: center; font-family: 'Montserrat', sans-serif; color: #888; padding: 2rem;">Товарів у цій категорії поки немає.</p>
        @endforelse
    </div>
</main>

<footer class="footer" id="contacts">
    <div class="footer-container">
        <div class="footer-col">
            <h4>Pâtisserie</h4>
            <p>Простір вишуканих десертів, створених вручну з любов'ю та турботою про якість.</p>
        </div>
        <div class="footer-col">
            <h4>Навігація</h4>
            <a href="{{ url('/') }}">Головна</a>
            <a href="#menu">Авторські шедеври</a>
            <a href="#contacts">Контакти</a>
        </div>
        <div class="footer-col">
            <h4>Контакти</h4>
            <p>📞 +380 93 123 4567</p>
            <p>✉️ info@patisserie.ua</p>
            <p>📍 м. Київ, вул. Хрещатик, 1</p>
        </div>
        <div class="footer-col">
            <h4>Графік</h4>
            <p>Працюємо для вас:<br>Щодня з 09:00 до 21:00</p>
        </div>
    </div>
    <div class="footer-bottom">
        &copy; {{ date('Y') }} Pâtisserie. Всі права захищені.
    </div>
</footer>

<div id="cart-sidebar" class="cart-sidebar">
    <div class="cart-sidebar-header">
        <h3>Ваш кошик</h3>
        <button id="close-cart" class="close-cart-btn">&times;</button>
    </div>
    <div class="cart-sidebar-items" id="cart-items-container">
        </div>
    <div class="cart-sidebar-footer">
        <form id="order-form" action="{{ url('/checkout') }}" method="POST">
            @csrf
            <input type="hidden" name="cart_data" id="cart-hidden-input">
            
            <div class="cart-user-info" style="margin-bottom: 1rem;">
                <input type="text" id="customer-name" name="name" placeholder="Твоє ім'я" required style="width: 100%; padding: 0.5rem; margin-bottom: 0.5rem; border: 1px solid var(--border-pink); border-radius: 0.25rem; font-family: 'Montserrat';">
                <input type="tel" id="customer-phone" name="phone" placeholder="Номер телефону" required style="width: 100%; padding: 0.5rem; border: 1px solid var(--border-pink); border-radius: 0.25rem; font-family: 'Montserrat';">
            </div>

            <div class="cart-total" style="margin-bottom: 1rem;">
                <span>Всього:</span>
                <span id="cart-total-price">0.00 грн</span>
            </div>
            <button type="submit" class="btn btn-primary checkout-btn" style="width: 100%;">Оформити замовлення</button>
        </form>
    </div>
</div>
<div id="cart-overlay" class="cart-overlay"></div>

<script src="{{ asset('assets/script.js') }}"></script>

</body>
</html>