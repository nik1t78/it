<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'public.home')->name('home');
Route::view('/news', 'public.news.index')->name('news.index');
Route::view('/appeals', 'public.appeals.index')->name('appeals.index');
