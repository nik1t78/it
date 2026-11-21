<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
class NewsController extends Controller {
 public function index(): View {$news=News::query()->latest('updated_at')->paginate(20);return view('admin.news.index',compact('news'));}
 public function create(): View {return view('admin.news.create');}
 public function store(Request $request): RedirectResponse {
  $data=$this->validated($request);
  $data['slug']=$this->uniqueSlug((string)(($data['slug']??null)?:$data['title']));
  $data['published_at']=$data['status']==='published'?($data['published_at']??now()):null;
  News::query()->create($data);
  return redirect()->route('admin.news.index')->with('status','Новость создана.');
 }
 public function edit(News $news): View {return view('admin.news.edit',compact('news'));}
 public function update(Request $request,News $news): RedirectResponse {
  $data=$this->validated($request,$news);
  $data['slug']=$this->uniqueSlug((string)(($data['slug']??null)?:$news->slug),$news->id);
  $data['published_at']=$data['status']==='published'?($data['published_at']??$news->published_at??now()):null;
  $news->update($data);
  return redirect()->route('admin.news.index')->with('status','Изменения сохранены.');
 }
 public function destroy(News $news): RedirectResponse {$news->delete();return redirect()->route('admin.news.index')->with('status','Новость перемещена в корзину.');}
 private function validated(Request $request,?News $news=null): array {
  return $request->validate([
   'title'=>['required','string','max:255'],
   'slug'=>['nullable','string','max:190',Rule::unique('news','slug')->ignore($news?->id)],
   'excerpt'=>['nullable','string','max:2000'],
   'body'=>['required','string'],
   'status'=>['required',Rule::in(['draft','published'])],
   'published_at'=>['nullable','date'],
  ]);
 }
 private function uniqueSlug(string $value,?int $ignoreId=null): string {
  $base=Str::slug($value)?:'news';$slug=$base;$suffix=2;
  while(News::withTrashed()->where('slug',$slug)->when($ignoreId,fn($q)=>$q->where('id','!=',$ignoreId))->exists()){$slug=$base.'-'.$suffix++;}
  return $slug;
 }
}
