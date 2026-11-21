<?php
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PublicNewsController;
use App\Http\Controllers\PublicPageController;
use Illuminate\Support\Facades\Route;
Route::get('/',HomeController::class)->name('home');
Route::get('/news',[PublicNewsController::class,'index'])->name('news.index');
Route::get('/news/{news:slug}',[PublicNewsController::class,'show'])->name('news.show');
Route::view('/appeals','public.appeals.index')->name('appeals.index');
Route::prefix('admin')->name('admin.')->group(function():void {
 Route::get('/login',[AdminLoginController::class,'create'])->name('login');
 Route::post('/login',[AdminLoginController::class,'store'])->middleware('throttle:10,1')->name('login.store');
 Route::middleware(['auth','role:admin,editor'])->group(function():void {
  Route::get('/',DashboardController::class)->name('dashboard');
  Route::post('/logout',[AdminLoginController::class,'destroy'])->name('logout');
  Route::resource('news',AdminNewsController::class)->except(['show']);
  Route::resource('pages',AdminPageController::class)->except(['show']);
 });
});
Route::get('/{page:slug}',[PublicPageController::class,'show'])->where('page','[a-z0-9-]+')->name('pages.show');
