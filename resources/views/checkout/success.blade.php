<!DOCTYPE html>
<html lang="uk">
<head>
    <title>Дякуємо за замовлення!</title>
    <style>
        body { text-align: center; font-family: sans-serif; padding: 100px; background: #fff5f3; color: #4a3b39; }
        .card { background: white; padding: 40px; border-radius: 12px; display: inline-block; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        a { color: #e98b7d; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>
    <div class="card">
        <h1>Дякуємо за оплату! 🎉</h1>
        <p>Ваше замовлення успішно прийнято в роботу.</p>
        <br>
        <a href="/">Повернутися на головну</a>
    </div>
    <script>
    // Очищаємо кошик у браузері користувача після успішного замовлення
    localStorage.removeItem('cart'); // або та назва ключа, яку ви використовуєте
</script>
</body>
</html>