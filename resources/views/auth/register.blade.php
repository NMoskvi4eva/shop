<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Реєстрація — Pâtisserie</title>
    <style>
        body { background-color: #f7ebe9; font-family: 'Segoe UI', sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .auth-card { background: white; padding: 40px; border-radius: 16px; box-shadow: 0 4px 15px rgba(233,139,125,0.15); width: 100%; max-width: 400px; text-align: center; }
        .auth-card h2 { color: #e98b7d; margin-bottom: 25px; font-weight: 600; }
        .form-group { text-align: left; margin-bottom: 15px; }
        .form-group label { display: block; font-size: 0.85rem; color: #887876; margin-bottom: 5px; }
        .form-group input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; font-size: 0.95rem; }
        .form-group input:focus { border-color: #e98b7d; outline: none; }
        .btn-submit { background: #e98b7d; color: white; border: none; width: 100%; padding: 12px; border-radius: 8px; font-weight: bold; cursor: pointer; text-transform: uppercase; margin-top: 15px; transition: 0.2s; }
        .btn-submit:hover { background: #d77a6c; }
        .auth-link { display: block; margin-top: 15px; font-size: 0.85rem; color: #887876; text-decoration: none; }
        .auth-link span { color: #e98b7d; }
    </style>
</head>
<body>

<div class="auth-card">
    <h2>Реєстрація</h2>
    <form action="/register" method="POST">
        @csrf
        <div class="form-group">
            <label>Ваше Ім'я *</label>
            <input type="text" name="name" placeholder="Ім'я" required>
        </div>
        <div class="form-group">
            <label>Email *</label>
            <input type="email" name="email" placeholder="example@gmail.com" required>
        </div>
        <div class="form-group">
            <label>Номер телефону</label>
            <input type="text" name="phone" placeholder="+380..." required>
        </div>
        <div class="form-group">
            <label>Пароль *</label>
            <input type="password" name="password" placeholder="Мінімум 6 символів" required>
        </div>
        <button type="submit" class="btn-submit">Зареєструватися</button>
    </form>
    <a href="/login" class="auth-link">Вже маєте акаунт? <span>Увійти</span></a>
</div>

</body>
</html>