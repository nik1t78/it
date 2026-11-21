@extends('layouts.public')
@section('title','Главная — Управление социальной политики Липецкой области')
@section('content')
<section class="hero"><p class="eyebrow">Официальный сайт</p><h1>Социальная поддержка и услуги жителям Липецкой области</h1><p class="hero-lead">Информация о мерах социальной поддержки, деятельности управления и возможностях получения государственных услуг.</p><a class="button button-light" href="{{ route('appeals.index') }}">Направить обращение</a></section>
<section class="section-block"><p class="eyebrow">Полезная информация</p><h2>Чем мы можем помочь</h2><div class="service-grid">
<a class="service-card" href="{{ route('appeals.index') }}"><span class="card-kicker">01</span><h3>Интернет-приёмная</h3><p>Электронное обращение в управление.</p></a>
<a class="service-card" href="{{ route('news.index') }}"><span class="card-kicker">02</span><h3>Новости</h3><p>Актуальная информация и объявления.</p></a>
<a class="service-card" href="#news"><span class="card-kicker">03</span><h3>Социальная поддержка</h3><p>Материалы о мерах поддержки жителей.</p></a>
</div></section>
<section class="section-block" id="news"><div class="section-heading"><div><p class="eyebrow">Последние публикации</p><h2>Новости</h2></div><a class="text-link" href="{{ route('news.index') }}">Все новости →</a></div>
@forelse($news as $item)<article class="news-row"><time>{{ $item->published_at?->format('d.m.Y') }}</time><div><h3><a href="{{ route('news.show',$item) }}">{{ $item->title }}</a></h3><p>{{ $item->excerpt }}</p></div></article>
@empty<p class="empty-state">Опубликованных новостей пока нет.</p>@endforelse
</section>
@endsection
