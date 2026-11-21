@extends('layouts.public')
@section('title','Новости')
@section('content')
<header class="page-heading"><p class="eyebrow">Информация</p><h1>Новости</h1></header>
<div class="public-news-list">
@forelse($news as $item)<article class="public-news-item"><time datetime="{{ $item->published_at?->toIso8601String() }}">{{ $item->published_at?->format('d.m.Y') }}</time><h2><a href="{{ route('news.show',$item) }}">{{ $item->title }}</a></h2><p>{{ $item->excerpt }}</p><a class="text-link" href="{{ route('news.show',$item) }}">Читать далее →</a></article>
@empty<p class="empty-state">Опубликованных новостей пока нет.</p>@endforelse
</div>
{{ $news->links() }}
@endsection
