<?php

namespace Modules\Site\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;

use Modules\Site\Jobs\Jft;

class ScheduleServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);
            if (!empty(config('Site.site_jft_parse_link'))) {
                $schedule->job(new Jft)
                    ->dailyAt('00:00')
                    ->timezone(config('Site.site_jft_timezone', 'Europe/Moscow'));
            }
        });
    }

    public function register()
    {
    }
}