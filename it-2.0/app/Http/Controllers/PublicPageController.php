<?php
namespace App\Http\Controllers;
use App\Models\Page;
use Illuminate\Contracts\View\View;
class PublicPageController extends Controller {
 public function show(Page $page): View {
  abort_unless($page->status==='published',404);
  return view('public.pages.show',compact('page'));
 }
}
