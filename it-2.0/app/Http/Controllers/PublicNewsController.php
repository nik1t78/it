<?php
namespace App\Http\Controllers;
use App\Models\News;
use Illuminate\Contracts\View\View;
class PublicNewsController extends Controller {
 public function index(): View {
  $news=News::query()->where('status','published')->whereNotNull('published_at')->where('published_at','<=',now())->latest('published_at')->paginate(10);
  return view('public.news.index',compact('news'));
 }
 public function show(News $news): View {
  abort_unless($news->status==='published' && $news->published_at && $news->published_at->lte(now()),404);
  return view('public.news.show',compact('news'));
 }
}
