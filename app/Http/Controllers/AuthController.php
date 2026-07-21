<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Відображення форми реєстрації
    public function showRegister()
    {
        return view('auth.register');
    }

    // Обробка реєстрації
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'phone'    => 'required|string|max:20',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect('/'); // Звичайних користувачів після реєстрації кидаємо на головну
    }

    // Відображення форми входу
    public function showLogin()
    {
        return view('auth.login');
    }

    // Обробка входу
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // 🎯 ПЕРЕВІРКА НА АДМІНІСТРАТОРА
            // Якщо увійшов користувач з email admin@gmail.com, перенаправляємо в адмінку
            if (Auth::user()->email === 'admin@gmail.com') {
                return redirect('/admin'); // Вкажіть тут правильний маршрут до вашої адмінки
            }

            // Якщо це звичайний клієнт — перенаправляємо на головну сторінку сайту
            return redirect('/');
        }

        return back()->withErrors([
            'email' => 'Невірний email або пароль.',
        ]);
    }

    // Вихід із системи
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}