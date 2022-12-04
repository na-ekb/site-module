<?php

namespace Modules\Site\Nova;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\BelongsTo;

use App\Nova\Resources\Resource;
use Modules\Site\Enums\ChangeFreq;

class PageMeta extends Resource
{
    /**
     * {@inheritdoc}
     */
    public static $model = \Modules\Site\Entities\PageMeta::class;

    /**
     * {@inheritdoc}
     */
    public static $title = 'title';

    /**
     * {@inheritdoc}
     */
    public static $search = [
        'id',
    ];

    /**
     * {@inheritdoc}
     */
    public static function group()
    {
        return __('site::admin/resources/groups.site');
    }

    /**
     * {@inheritdoc}
     */
    public static function label()
    {
        return __('site::admin/resources/pages.meta.metas');
    }

    /**
     * {@inheritdoc}
     */
    public static function singularLabel()
    {
        return __('site::admin/resources/pages.meta.meta');
    }

    /**
     * {@inheritdoc}
     */
    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id')
                ->exceptOnForms()
                ->sortable(),
            Text::make(__('site::admin/resources/pages.fields.title'), 'title')
                ->translatable()
                ->sortable()
                ->rules(['max:70']),
            Text::make(__('site::admin/resources/pages.meta.keywords'), 'keywords')
                ->translatable()
                ->hideFromIndex()
                ->rules(['max:200']),
            Text::make(__('site::admin/resources/pages.meta.small_desc'), 'small_desc')
                ->translatable()
                ->rules(['max:70']),
            Text::make(__('site::admin/resources/pages.meta.description'), 'description')
                ->translatable()
                ->hideFromIndex()
                ->rules(['max:200']),
            Code::make(__('site::admin/resources/pages.meta.meta_tags'), 'meta_tags')
                ->translatable()
                ->hideFromIndex(),
            Code::make(__('site::admin/resources/pages.meta.scripts'), 'scripts')
                ->translatable()
                ->hideFromIndex(),
            Boolean::make(__('site::admin/resources/pages.meta.sitemap'), 'sitemap')
                ->sortable()
                ->rules([
                    'required_if:sitemap,1',
                    'boolean'
                ])
                ->size('w-1/3')
                ->help(__('site::admin/resources/pages.help.sitemap')),
            Select::make(__('site::admin/resources/pages.meta.changefreq'), 'changefreq')
                ->options(ChangeFreq::asSelectArray())
                ->rules(
                    'required_if:sitemap,1',
                    Rule::in(ChangeFreq::getValues()),
                )
                ->hideFromIndex()
                ->sortable()
                ->size('w-1/3')
                ->help(__('site::admin/resources/pages.help.changefreq')),
            Number::make(__('site::admin/resources/pages.meta.priority'), 'priority')
                ->sortable()
                ->min(0)
                ->max(1)
                ->step(0.1)
                ->rules([
                    'required_if:sitemap,1',
                    'min:0',
                    'max:1'
                ])
                ->size('w-1/3')
                ->help(__('site::admin/resources/pages.help.priority')),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function actions(Request $request)
    {
        return [];
    }
}
