<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Перенаправлення на оплату...</title>
</head>
<body onload="document.getElementById('liqpay-form').submit();" style="text-align: center; font-family: sans-serif; padding-top: 100px; background: #fff5f3;">
    
    <h2 style="color: #4a3b39;">Зачекайте, перенаправляємо на сторінку оплати LiqPay...</h2>
    <p style="color: #887876;">Будь ласка, не закривайте цю сторінку.</p>

   <form id="liqpay-form" method="POST" action="https://www.liqpay.ua/api/3/checkout" accept-charset="utf-8">
    <input type="hidden" name="data" value="{{ $data }}" />
    <input type="hidden" name="signature" value="{{ $signature }}" />
</form>
<script>document.getElementById('liqpay-form').submit();</script>

</body>
</html>