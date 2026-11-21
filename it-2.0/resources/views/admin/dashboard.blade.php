@extends('layouts.public')
@section('title', 'Панель управления — IT 2.0')
@section('content')
<div class="admin-topline"><div><p class="eyebrow">Администрирование</p><h1>Панель управления</h1><p class="muted-dark">Здравствуйте, {{ auth()->user()->name }}.</p></div><form method="post" action="{{ route('admin.logout') }}">@csrf<button class="button button-outline" type="submit">Выйти</button></form></div>
<section class="section-block"><h2>Управление сайтом</h2><div class="service-grid"><article class="service-card"><span class="card-kicker">01</span><h3>Страницы</h3><p>Раздел управления страницами будет подключён следующим этапом.</p></article><article class="service-card"><span class="card-kicker">02</span><h3>Новости</h3><p>Управление публикациями и расписанием размещения.</p></article><article class="service-card"><span class="card-kicker">03</span><h3>Пользователи</h3><p>Роли, доступы и учётные записи сотрудников.</p></article></div></section>
@endsection
