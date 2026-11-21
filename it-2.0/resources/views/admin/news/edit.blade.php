@extends('layouts.public')
@section('title','Редактирование новости')
@section('content')
<header class="page-heading"><p class="eyebrow">Контент</p><h1>Редактирование новости</h1></header>
<form class="admin-form" method="post" action="{{ route('admin.news.update',$news) }}">@csrf @method('PUT')
@include('admin.news._form',['news'=>$news])
<div class="form-actions"><button class="button button-primary" type="submit">Сохранить изменения</button><a href="{{ route('admin.news.index') }}">Отмена</a></div>
</form>
@endsection
