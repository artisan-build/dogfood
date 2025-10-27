<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::get('/embeddable-links-demo', function () {
    return view('embeddable-links::demo');
})->name('embeddable-links.demo');
