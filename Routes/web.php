<?php

Route::get('{slug?}', [Modules\Site\Http\Controllers\SiteController::class, 'page'])->name('pages');