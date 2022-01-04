<?php

namespace Modules\Site\Http\Widgets;

use Carbon\Carbon;
use Illuminate\Http\Request;

use Arrilot\Widgets\AbstractWidget;

use Modules\Site\Entities\Jft as JftModel;
use Modules\Site\Jobs\Jft as JftJob;

class Jft extends AbstractWidget
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
        if (!JftModel::today()->exists()) {
            JftJob::dispatchSync();
        }
        return view('site::partials.jft', [
            'jft' => JftModel::today()->first(),
        ]);
    }
}