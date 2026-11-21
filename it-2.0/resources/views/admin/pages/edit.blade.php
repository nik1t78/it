@extends('layouts.public')
@section('title','Редактирование страницы')
@section('content')
<header class="page-heading"><p class="eyebrow">Структура сайта</p><h1>Редактирование страницы</h1></header>
<form class="admin-form" method="post" action="{{ route('admin.pages.update',$page) }}">@csrf @method('PUT')
@include('admin.pages._form',['page'=>$page,'parents'=>$parents])
<div class="form-actions"><button class="button button-primary" type="submit">Сохранить изменения</button><a href="{{ route('admin.pages.index') }}">Отмена</a></div>
</form>
@endsection
