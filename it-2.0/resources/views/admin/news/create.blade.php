@extends('layouts.public')
@section('title','Новая новость')
@section('content')
<header class="page-heading"><p class="eyebrow">Контент</p><h1>Новая новость</h1></header>
<form class="admin-form" method="post" action="{{ route('admin.news.store') }}">@csrf
@include('admin.news._form',['news'=>null])
<div class="form-actions"><button class="button button-primary" type="submit">Сохранить</button><a href="{{ route('admin.news.index') }}">Отмена</a></div>
</form>
@endsection
