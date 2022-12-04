<?php

namespace Modules\Site\Console;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;

use Modules\Site\Entities\Page;

class GenerateSitemap extends Command
{

    /**
    * The console command name.
    *
    * @var string
    */
    protected $signature = 'sitemap:generate';

    /**
    * The console command description.
    *
    * @var string
    */
    protected $description = 'Generate the sitemap.';

    /**
    * Execute the console command.
    *
    * @return mixed
    */
    public function handle()
    {
        Sitemap::create()
            ->add(Page::all())
            ->writeToFile(public_path('sitemap.xml'));

        return 'Ok';
    }
}