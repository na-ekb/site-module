<?php

namespace Modules\Site\Http\Widgets;

use Carbon\Carbon;
use Illuminate\Http\Request;

use Arrilot\Widgets\AbstractWidget;
use Modules\Site\Http\Requests\FeedbackRequest;

class Feedback extends AbstractWidget
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
        'sendFeedback' => FeedbackRequest::class
    ];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run(Request $request)
    {
        return view('site::widgets.feedback');
    }

    public function sendFeedback(FeedbackRequest $request) {
        return back()->with('success', 'Сообщение отправлено.');
    }
}