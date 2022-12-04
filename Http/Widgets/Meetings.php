<?php

namespace Modules\Site\Http\Widgets;

use App\Models\Meeting;
use App\Models\MeetingDayFormat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

use Arrilot\Widgets\AbstractWidget;

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
        $cities = Meeting::pluck('city')->unique()->filter()->toArray();
        $locations = Meeting::pluck('location')->unique()->filter()->toArray();

        if ($request->exists('date') || $request->exists('city')) {
            $validator = Validator::make($request->all(), [
                'date' => 'nullable|date',
                'city' => [
                    'nullable',
                    Rule::in($cities)
                ],
                'location' => [
                    'nullable',
                    Rule::in($locations)
                ]
            ]);
            if ($validator->fails()) {
                abort(404);
            }
            $currentDate = Carbon::parse($request->get('date'));
        } else {
            $currentDate = Carbon::now();
        }

        $dayOfWeek = $currentDate->dayOfWeek;

        if (empty($request->all())) {
            $meetings = MeetingDay::day()->with('meeting', 'meetingDayFormat')->orderBy('time')->get();
        } else {
            $meetings = MeetingDay::filter($request->all())->with('meeting', 'meetingDayFormat')->orderBy('time')->get();
        }

        return view('site::widgets.meetings', [
            'meetingDays'   => $meetings,
            'cities'        => $cities,
            'city'          => $request->get('city') ?? 0,
            'locations'     => $locations,
            'location'      => $request->get('location') ?? 0,
            'time'          => Carbon::now()->format('H:i'),
            'date'          => $currentDate->isoFormat('D MMMM Y'),
            'dayOfWeek'     => $dayOfWeek,
            'weekdays'      => Weekdays::asSelectArray()
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