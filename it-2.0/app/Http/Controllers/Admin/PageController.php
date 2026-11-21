<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
class PageController extends Controller {
 public function index(): View {$pages=Page::query()->with('parent')->orderBy('sort_order')->latest('updated_at')->paginate(20);return view('admin.pages.index',compact('pages'));}
 public function create(): View {$parents=Page::query()->orderBy('title')->get();return view('admin.pages.create',compact('parents'));}
 public function store(Request $request): RedirectResponse {
  $data=$this->validated($request);$data['slug']=$this->uniqueSlug((string)(($data['slug']??null)?:$data['title']));
  $data['show_in_menu']=$request->boolean('show_in_menu');
  Page::query()->create($data);
  return redirect()->route('admin.pages.index')->with('status','Страница создана.');
 }
 public function edit(Page $page): View {$parents=Page::query()->whereKeyNot($page->id)->orderBy('title')->get();return view('admin.pages.edit',compact('page','parents'));}
 public function update(Request $request,Page $page): RedirectResponse {
  $data=$this->validated($request,$page);
  if((int)($data['parent_id']??0)===$page->id){return back()->withErrors(['parent_id'=>'Страница не может быть родителем самой себе.'])->withInput();}
  $data['slug']=$this->uniqueSlug((string)(($data['slug']??null)?:$page->slug),$page->id);
  $data['show_in_menu']=$request->boolean('show_in_menu');
  $page->update($data);
  return redirect()->route('admin.pages.index')->with('status','Изменения сохранены.');
 }
 public function destroy(Page $page): RedirectResponse {$page->delete();return redirect()->route('admin.pages.index')->with('status','Страница перемещена в корзину.');}
 private function validated(Request $request,?Page $page=null): array {
  return $request->validate([
   'title'=>['required','string','max:255'],'slug'=>['nullable','string','max:190',Rule::unique('pages','slug')->ignore($page?->id)],
   'body'=>['nullable','string'],'status'=>['required',Rule::in(['draft','published'])],
   'sort_order'=>['required','integer','min:0','max:100000'],
   'parent_id'=>['nullable','integer',Rule::exists('pages','id')],
  ]);
 }
 private function uniqueSlug(string $value,?int $ignoreId=null): string {
  $base=Str::slug($value)?:'page';$slug=$base;$suffix=2;
  while(Page::withTrashed()->where('slug',$slug)->when($ignoreId,fn($q)=>$q->where('id','!=',$ignoreId))->exists()){$slug=$base.'-'.$suffix++;}
  return $slug;
 }
}
