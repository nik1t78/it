<?php
namespace App\Http\Controllers;
use App\Models\News;
use Illuminate\Contracts\View\View;
class HomeController extends Controller {
 public function __invoke(): View {
  $news=News::query()->where('status','published')->whereNotNull('published_at')->where('published_at','<=',now())->latest('published_at')->limit(4)->get();
  return view('public.home',compact('news'));
 }
}
