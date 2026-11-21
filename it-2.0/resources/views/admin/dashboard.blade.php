@extends('layouts.public')
@section('title','Панель управления — IT 2.0')
@section('content')
<div class="admin-topline"><div><p class="eyebrow">Администрирование</p><h1>Панель управления</h1><p class="muted-dark">Здравствуйте, {{ auth()->user()->name }}.</p></div><form method="post" action="{{ route('admin.logout') }}">@csrf<button class="button button-outline" type="submit">Выйти</button></form></div>
<section class="section-block"><h2>Управление сайтом</h2><div class="service-grid">
<a class="service-card" href="{{ route('admin.pages.index') }}"><span class="card-kicker">01</span><h3>Страницы</h3><p>Создание, редактирование и публикация информационных разделов.</p></a>
<a class="service-card" href="{{ route('admin.news.index') }}"><span class="card-kicker">02</span><h3>Новости</h3><p>Создание, редактирование и публикация новостей.</p></a>
<a class="service-card" href="#"><span class="card-kicker">03</span><h3>Пользователи</h3><p>Роли, доступы и учётные записи сотрудников — следующий этап.</p></a>
</div></section>
@endsection
