<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Вход в систему управления — IT 2.0</title>
    <link rel="stylesheet" href="{{ asset('css/site.css') }}">
</head>
<body class="admin-auth-page">
<main class="auth-card">
    <a class="auth-brand" href="{{ route('home') }}">Управление социальной политики<br><span>Липецкой области</span></a>
    <p class="eyebrow">Защищённая зона</p>
    <h1>Вход для сотрудников</h1>
    @if ($errors->any())
        <div class="form-errors" role="alert">
            @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
        </div>
    @endif
    <form method="post" action="{{ route('admin.login.store') }}">
        @csrf
        <label for="email">Электронная почта</label>
        <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="username" required autofocus>
        <label for="password">Пароль</label>
        <input id="password" name="password" type="password" autocomplete="current-password" required>
        <label class="remember"><input type="checkbox" name="remember" value="1"> Запомнить меня</label>
        <button class="button button-primary" type="submit">Войти</button>
    </form>
    <a class="back-link" href="{{ route('home') }}">← Вернуться на сайт</a>
</main>
</body>
</html>
