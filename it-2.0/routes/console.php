<?php

use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

Artisan::command('it2:about', function () {
    $this->info('IT 2.0 — Laravel 12 + Blade + MySQL');
})->purpose('Информация о новой версии сайта');

Artisan::command('it2:make-admin', function () {
    $name = trim((string) $this->ask('Имя администратора'));
    $email = Str::lower(trim((string) $this->ask('Электронная почта')));
    $password = (string) $this->secret('Новый пароль (не менее 12 символов)');

    if ($name === '' || ! filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 12) {
        $this->error('Проверьте имя, email и длину пароля (минимум 12 символов).');
        return 1;
    }

    if (User::query()->where('email', $email)->exists()) {
        $this->error('Пользователь с таким email уже существует.');
        return 1;
    }

    if (! $this->confirm('Создать учётную запись администратора в текущей БД?')) {
        $this->warn('Операция отменена.');
        return 0;
    }

    User::query()->create([
        'name' => $name,
        'email' => $email,
        'password' => Hash::make($password),
        'role' => 'admin',
        'is_active' => true,
    ]);

    $this->info('Администратор создан.');
    return 0;
})->purpose('Интерактивно создать первого администратора IT 2.0');
