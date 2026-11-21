@extends('layouts.public')
@section('title','Новости — панель управления')
@section('content')
<div class="admin-topline"><div><p class="eyebrow">Контент</p><h1>Новости</h1><p class="muted-dark">Создание, редактирование и публикация материалов.</p></div><a class="button button-primary" href="{{ route('admin.news.create') }}">Добавить новость</a></div>
@if(session('status'))<p class="notice-success" role="status">{{ session('status') }}</p>@endif
<div class="admin-table-wrap"><table class="admin-table"><thead><tr><th>Заголовок</th><th>Статус</th><th>Дата публикации</th><th>Действия</th></tr></thead><tbody>
@forelse($news as $item)<tr><td><strong>{{ $item->title }}</strong><small>{{ $item->slug }}</small></td><td>{{ $item->status==='published'?'Опубликована':'Черновик' }}</td><td>{{ $item->published_at?->format('d.m.Y H:i')??'—' }}</td><td class="actions"><a href="{{ route('admin.news.edit',$item) }}">Изменить</a><form method="post" action="{{ route('admin.news.destroy',$item) }}" onsubmit="return confirm('Переместить новость в корзину?')">@csrf @method('DELETE')<button type="submit" class="link-danger">Удалить</button></form></td></tr>
@empty<tr><td colspan="4">Новостей пока нет.</td></tr>@endforelse
</tbody></table></div>
{{ $news->links() }}
@endsection
