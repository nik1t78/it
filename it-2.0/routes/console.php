<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('it2:about', function () {
    $this->info('IT 2.0 — Laravel 12 + Blade + MySQL');
})->purpose('Информация о новой версии сайта');
