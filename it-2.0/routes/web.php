<?php
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\AdminLoginController;
use Illuminate\Support\Facades\Route;
Route::view('/', 'public.home')->name('home');
Route::view('/news', 'public.news.index')->name('news.index');
Route::view('/appeals', 'public.appeals.index')->name('appeals.index');
Route::prefix('admin')->name('admin.')->group(function (): void {
 Route::get('/login', [AdminLoginController::class, 'create'])->name('login');
 Route::post('/login', [AdminLoginController::class, 'store'])->middleware('throttle:10,1')->name('login.store');
 Route::middleware(['auth', 'role:admin,editor'])->group(function (): void {
  Route::get('/', DashboardController::class)->name('dashboard');
  Route::post('/logout', [AdminLoginController::class, 'destroy'])->name('logout');
 });
});
