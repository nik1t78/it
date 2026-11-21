@if($errors->any())<div class="form-errors" role="alert">@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>@endif
<label for="title">Название страницы</label><input id="title" name="title" value="{{ old('title',$page?->title) }}" maxlength="255" required>
<label for="slug">Адрес страницы (slug)</label><input id="slug" name="slug" value="{{ old('slug',$page?->slug) }}" maxlength="190" placeholder="Создаётся из названия, если оставить пустым">
<label for="parent_id">Родительская страница</label><select id="parent_id" name="parent_id"><option value="">Без родительской страницы</option>@foreach($parents as $parent)<option value="{{ $parent->id }}" @selected((string)old('parent_id',$page?->parent_id)===(string)$parent->id)>{{ $parent->title }}</option>@endforeach</select>
<label for="body">Содержание</label><textarea id="body" name="body" rows="14">{{ old('body',$page?->body) }}</textarea>
<label for="status">Статус</label><select id="status" name="status" required><option value="draft" @selected(old('status',$page?->status??'draft')==='draft')>Черновик</option><option value="published" @selected(old('status',$page?->status??'draft')==='published')>Опубликована</option></select>
<label for="sort_order">Порядок сортировки</label><input id="sort_order" type="number" name="sort_order" min="0" max="100000" value="{{ old('sort_order',$page?->sort_order??0) }}" required>
<label class="remember"><input type="checkbox" name="show_in_menu" value="1" @checked(old('show_in_menu',$page?->show_in_menu??false))> Показывать в меню</label>
