<?php

namespace Modules\Site\Http\Widgets;

use Carbon\Carbon;
use Illuminate\Http\Request;

use Arrilot\Widgets\AbstractWidget;

class Sharing extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run(Request $request)
    {
        return view('site::widgets.sharing');
    }
}