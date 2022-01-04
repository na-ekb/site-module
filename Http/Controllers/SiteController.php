<?php

namespace Modules\Site\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Site\Entities\Page;

class SiteController extends Controller
{
    public function page(?string $slug = null) {
        $slug = $slug ?? 'home';
        $page = Page::with('pageMeta')->where('slug', $slug)->firstOrFail();

        $widgets = config('widgets');

        $page->content = preg_replace_callback('/\[\[(.+)\]\]/m', function($matches) use ($widgets) {
            if (empty($widgets[$matches[1]])) {
                return __('site::widgets.not_found', [
                    'name' => $matches[1]
                ]);
            }
            return app('arrilot.widget')->run($widgets[$matches[1]]);
        }, $page->content);

        return view('site::page', ['page' => $page]);
    }
}
