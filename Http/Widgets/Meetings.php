<?php

namespace Modules\Site\Http\Widgets;

use Illuminate\Http\Request;

use Arrilot\Widgets\AbstractWidget;
use Carbon\Carbon;

use App\Models\MeetingDay;
use App\Enums\Weekdays;

class Meetings extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    /**
     * Widget actions
     *
     * @var string[]
     */
    public $actions = [
        'partialMeetings'
    ];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run(Request $request)
    {
        $dayOfWeek = Carbon::now()->dayOfWeek;
        $weekdaysArr = Weekdays::asSelectArray();
        $key = array_search($dayOfWeek, array_keys($weekdaysArr), true);
        if ($key !== false) {
            $first = array_slice($weekdaysArr, $key, null, true);
            $second = array_diff($weekdaysArr, $first);
            $weekdaysArr = $first + $second;
        }
        if (empty($request->all())) {
            $meetings = MeetingDay::day()->with('meeting', 'meetingDayFormat')->orderBy('time')->get();
        } else {
            $meetings = MeetingDay::filter($request->all())->with('meeting', 'meetingDayFormat')->orderBy('time')->get();
        }

        return view('site::widgets.meetings', [
            'meetingDays'   => $meetings,
            'time'          => Carbon::now()->format('H:i'),
            'date'          => \Date::now()->format('j F Y'),
            'dayOfWeek'     => $dayOfWeek,
            'weekdays'      => $weekdaysArr
        ]);
    }

    /**
     * Partial for ajax
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function partialMeetings() {
        return view('site::widgets.partials.meetings', [
            'meetingDays' => MeetingDay::filter(request()->all())->with('meeting')->orderBy('time')->get(),
        ]);
    }
}