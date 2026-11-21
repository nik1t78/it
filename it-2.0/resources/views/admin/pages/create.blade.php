@extends('layouts.public')
@section('title','Новая страница')
@section('content')
<header class="page-heading"><p class="eyebrow">Структура сайта</p><h1>Новая страница</h1></header>
<form class="admin-form" method="post" action="{{ route('admin.pages.store') }}">@csrf
@include('admin.pages._form',['page'=>null,'parents'=>$parents])
<div class="form-actions"><button class="button button-primary" type="submit">Сохранить</button><a href="{{ route('admin.pages.index') }}">Отмена</a></div>
</form>
@endsection
