@if($errors->any())<div class="form-errors" role="alert">@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>@endif
<label for="title">Заголовок</label><input id="title" name="title" value="{{ old('title',$news?->title) }}" maxlength="255" required>
<label for="slug">Адрес страницы (slug)</label><input id="slug" name="slug" value="{{ old('slug',$news?->slug) }}" maxlength="190" placeholder="Будет создан автоматически из заголовка">
<label for="excerpt">Краткое описание</label><textarea id="excerpt" name="excerpt" rows="3" maxlength="2000">{{ old('excerpt',$news?->excerpt) }}</textarea>
<label for="body">Текст публикации</label><textarea id="body" name="body" rows="12" required>{{ old('body',$news?->body) }}</textarea>
<label for="status">Статус</label><select id="status" name="status" required><option value="draft" @selected(old('status',$news?->status??'draft')==='draft')>Черновик</option><option value="published" @selected(old('status',$news?->status??'draft')==='published')>Опубликована</option></select>
<label for="published_at">Дата и время публикации</label><input id="published_at" type="datetime-local" name="published_at" value="{{ old('published_at',$news?->published_at?->format('Y-m-d\TH:i')) }}">
