<?php

namespace Modules\Site\Http\Widgets;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
            try {
                JftJob::dispatchSync();
            } catch (\Throwable $e) {
                Log::error("Jft sync error {$e->getMessage()}");
            }
        }
        return view('site::widgets.jft', [
            'jft' => JftModel::today()->first(),
        ]);
    }
}