<?php

namespace Modules\Site\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Http;

use Carbon\Carbon;

use Modules\Site\Entities\Jft as JftModel;


class Jft implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $url = config('Site.site_jft_parse_link');
        if (!empty($url) && !JftModel::today()->exists()) {
            $response = Http::get($url);
            $content = str_replace('<hr />', '', $response->body());
            $content = str_replace("\r", '', $content);
            $content = str_replace("\n", '', $content);
            $content = str_replace("\t", '', $content);

            $header = explode('<div class="eg_header">', $content);
            $header = array_pop($header);
            $header = explode('</div>', $header);
            $header = array_shift($header);
            $header = strip_tags($header);

            $quote = explode('<div class="eg_quote">', $content);
            $quote = array_pop($quote);
            $quote = explode('</div>', $quote);
            $quote = array_shift($quote);
            $quote = strip_tags($quote);

            $from = explode('<div class="eg_quote_from">', $content);
            $from = array_pop($from);
            $from = explode('</div>', $from);
            $from = array_shift($from);
            $from = strip_tags($from);

            JftModel::create([
                'header' => $header,
                'quote'  => html_entity_decode($quote),
                'from'   => $from
            ]);
        }
    }
}
