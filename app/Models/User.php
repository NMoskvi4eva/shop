<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Поля, які можна масово заповнювати (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone', 
        'password',
    ];

    /**
     * Поля, які слід приховати при серіалізації (наприклад, у JSON-відповідях).
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Отримання атрибутів, які потребують приведення типів.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}