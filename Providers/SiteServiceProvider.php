<?php

namespace Modules\Site\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Routing\Router;

use Laravel\Nova\Fields\Select;
use Laravel\Nova\Nova;
use Laravel\Nova\Panel;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Timezone;
use OptimistDigital\NovaSettings\NovaSettings;
use Spatie\ResponseCache\Middlewares\CacheResponse;
use Spatie\ResponseCache\Middlewares\DoNotCacheResponse;

use Modules\Site\Nova\Page;
use Modules\Site\Nova\PageMeta;
use Modules\Site\Http\Widgets\Meetings;
use Modules\Site\Http\Widgets\CountMeetings;
use Modules\Site\Http\Widgets\Jft;
use Modules\Site\Http\Widgets\Sharing;
use Modules\Site\Http\Widgets\Feedback;
use Modules\Site\Console\GenerateSitemap;
use App\Models\Setting;

class SiteServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'Site';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'site';

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));

        $router = $this->app->make(Router::class);
        if (!$this->app->isLocal()) {
            $router->pushMiddlewareToGroup('web', CacheResponse::class);
        }
        $router->aliasMiddleware('doNotCacheResponse', DoNotCacheResponse::class);


        config([
            'Site' => Setting::where('key', 'like', 'site_%')
                ->get()
                ->keyBy('key')
                ->transform(function ($setting) {
                    return $setting->value;
                })
                ->toArray(),
            'nova-group-order' => array_merge(
                config('nova-group-order') ?? [],
                [
                    __('site::admin/resources/groups.site') => 1,
                ]
            ),
            'widgets' => array_merge(
                config('widgets') ?? [],
                [
                    'meetings'  => Meetings::class,
                    'jft'       => Jft::class,
                    'count'     => CountMeetings::class,
                    'sharing'   => Sharing::class,
                    'feedback'  => Feedback::class,
                ]
            )
        ]);

        NovaSettings::addSettingsFields([
            Panel::make(__('site::admin/settings.primary.title'), [
                Text::make(__('site::admin/settings.primary.footer'), 'site_footer'),
            ]),
            Panel::make(__('site::admin/settings.contacts.title'), [
                Text::make(__('site::admin/settings.contacts.phone'), 'site_phone'),
                Text::make(__('site::admin/settings.contacts.email'), 'site_email'),
                Text::make(__('site::admin/settings.contacts.whatsapp'), 'site_whatsapp'),
                Text::make(__('site::admin/settings.contacts.telegram'), 'site_telegram'),
            ]),
            Panel::make(__('site::admin/settings.widget_jft.title'), [
                Text::make(__('site::admin/settings.widget_jft.link'), 'site_jft_link'),
                Text::make(__('site::admin/settings.widget_jft.parse_link'), 'site_jft_parse_link')
                    ->help(
                        __('site::admin/settings.widget_jft.parse_link_help')
                    ),
                Timezone::make(__('site::admin/settings.widget_jft.timezone'), 'site_jft_timezone')
                    ->help(
                        __('site::admin/settings.widget_jft.timezone_help')
                    ),
            ]),
            Panel::make(__('site::admin/settings.widget_feedback.title'), [
                Select::make(__('site::admin/settings.widget_feedback.provider'), 'site_feedback_provider')
                    ->options([
                        'smtp'  => 'SMTP',
                        'tg'    => 'Telegram',
                    ])
                    ->help(
                        __('site::admin/settings.widget_feedback.provider_help')
                    ),
            ]),
        ], [], 'site');

        $this->commands([
            GenerateSitemap::class,
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
        Nova::resources([
            Page::class,
            PageMeta::class,
        ]);
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            module_path($this->moduleName, 'Config/config.php') => config_path($this->moduleNameLower . '.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path($this->moduleName, 'Config/config.php'), $this->moduleNameLower
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/' . $this->moduleNameLower);

        $sourcePath = module_path($this->moduleName, 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ], ['views', $this->moduleNameLower . '-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/' . $this->moduleNameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->moduleNameLower);
        } else {
            $this->loadTranslationsFrom(module_path($this->moduleName, 'Resources/lang'), $this->moduleNameLower);
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }

    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (\Config::get('view.paths') as $path) {
            if (is_dir($path . '/modules/' . $this->moduleNameLower)) {
                $paths[] = $path . '/modules/' . $this->moduleNameLower;
            }
        }
        return $paths;
    }
}
