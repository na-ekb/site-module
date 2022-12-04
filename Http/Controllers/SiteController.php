<?php

namespace Modules\Site\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\MeetingDay;

use Laravel\Nova\Nova;
use Modules\Site\Entities\Page;

class SiteController extends Controller
{
    public function page(?string $slug = null) {
        $slug = $slug ?? 'home';
        $page = Page::with('pageMeta')->where('slug', $slug)->firstOrFail();

        $widgets = config('widgets');

        $page->content = preg_replace_callback('/\[\[(.+?)\]\]/m', function($matches) use ($widgets) {
            if (empty($widgets[$matches[1]])) {
                return __('site::widgets.not_found', [
                    'name' => $matches[1]
                ]);
            }
            return \Widget::run($widgets[$matches[1]]);
        }, $page->content);

        return view('site::page', ['page' => $page]);
    }

    public function widgetAction(Request $request, string $name, string $action) {
        $widgets = config('widgets');
        if (empty($widgets[$name]) || !method_exists($widgets[$name], $action)) {
            abort(404);
        }

        $widget = new $widgets[$name];
        if (!in_array($action, array_keys($widget->actions)) && !in_array($action, $widget->actions)) {
            abort(404);
        }

        if (in_array($action, array_keys($widget->actions))) {
            $request = $widget->actions[$action]::createFrom($request);
            $this->validate($request, $request->rules());
            return $widget->{$action}($request);
        }

        return $widget->{$action}();
    }
}
