<?php

namespace Modules\Site\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Str;

use Spatie\Sitemap\Tags\Url;
use Spatie\Sitemap\Sitemap as SitemapBuilder;

use Modules\Site\Entities\Page;
use Modules\Site\Enums\ChangeFreq;



class Sitemap implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $sitemap = SitemapBuilder::create();
        $pages = Page::with('pageMeta')->each(function ($page) use ($sitemap) {
            $sitemap->add(
                Url::create("/{$page->slug}")
                ->setLastModificationDate($page->updated_at)
                ->setChangeFrequency(Str::lower(ChangeFreq::getKey($page->pageMeta->changefreq)))
                ->setPriority($page->pageMeta->priority)
            );
        });
        $sitemap->writeToFile(public_path('sitemap.xml'));
    }
}
