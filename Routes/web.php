<?php

Route::get('{slug?}', [Modules\Site\Http\Controllers\SiteController::class, 'page'])->name('pages');
Route::match(['get', 'post'],'widget/{name}/{action}', [Modules\Site\Http\Controllers\SiteController::class, 'widgetAction'])->name('widgetAction');
