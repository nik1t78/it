@extends('layouts.public')
@section('title',$page->title)
@section('content')
<article class="article-page"><p class="eyebrow">Информация</p><h1>{{ $page->title }}</h1><div class="article-body">{!! nl2br(e($page->body??'')) !!}</div></article>
@endsection
