@extends('layouts.public')
@section('title',$news->title)
@section('content')
<article class="article-page">
<p class="eyebrow">Новости · {{ $news->published_at?->format('d.m.Y') }}</p>
<h1>{{ $news->title }}</h1>
@if($news->excerpt)<p class="article-lead">{{ $news->excerpt }}</p>@endif
<div class="article-body">{!! nl2br(e($news->body)) !!}</div>
<a class="text-link" href="{{ route('news.index') }}">← Все новости</a>
</article>
@endsection
