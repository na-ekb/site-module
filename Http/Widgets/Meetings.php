<?php

namespace Modules\Site\Http\Widgets;

use Carbon\Carbon;
use Illuminate\Http\Request;

use Arrilot\Widgets\AbstractWidget;

use App\Models\MeetingDay;

class Meetings extends AbstractWidget
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
        return view('site::partials.meetings', [
            'meetingDays' => MeetingDay::day(Carbon::now())->with('meeting')->get(),
        ]);
    }
}