@extends('layouts.public')
@section('title','Страницы — панель управления')
@section('content')
<div class="admin-topline"><div><p class="eyebrow">Структура сайта</p><h1>Страницы</h1><p class="muted-dark">Управление информационными разделами сайта.</p></div><a class="button button-primary" href="{{ route('admin.pages.create') }}">Добавить страницу</a></div>
@if(session('status'))<p class="notice-success" role="status">{{ session('status') }}</p>@endif
<div class="admin-table-wrap"><table class="admin-table"><thead><tr><th>Название</th><th>Статус</th><th>В меню</th><th>Порядок</th><th>Действия</th></tr></thead><tbody>
@forelse($pages as $item)<tr><td><strong>{{ $item->title }}</strong><small>/{{ $item->slug }} @if($item->parent) · родитель: {{ $item->parent->title }} @endif</small></td><td>{{ $item->status==='published'?'Опубликована':'Черновик' }}</td><td>{{ $item->show_in_menu?'Да':'Нет' }}</td><td>{{ $item->sort_order }}</td><td class="actions"><a href="{{ route('admin.pages.edit',$item) }}">Изменить</a><form method="post" action="{{ route('admin.pages.destroy',$item) }}" onsubmit="return confirm('Переместить страницу в корзину?')">@csrf @method('DELETE')<button type="submit" class="link-danger">Удалить</button></form></td></tr>
@empty<tr><td colspan="5">Страниц пока нет.</td></tr>@endforelse
</tbody></table></div>{{ $pages->links() }}
@endsection
