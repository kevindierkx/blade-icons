<?php

declare(strict_types=1);

use BladeUI\Icons\Http\Controllers\IconsController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => []], function () {
    Route::get('/icons/{set}/{name}.svg', IconsController::class)->name('blade-icons.icon');
});
