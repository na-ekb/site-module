<?php

namespace Modules\Site\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\BelongsTo;

use App\Nova\Resources\Resource;

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
                ->sortable()
                ->size('w-full'),
            Text::make(__('site::admin/resources/pages.fields.title'), 'title')
                ->translatable()
                ->sortable()
                ->rules(['max:70'])
                ->size('w-full'),
            Text::make(__('site::admin/resources/pages.meta.keywords'), 'keywords')
                ->translatable()
                ->hideFromIndex()
                ->rules(['max:200'])
                ->size('w-full'),
            Text::make(__('site::admin/resources/pages.meta.small_desc'), 'small_desc')
                ->translatable()
                ->rules(['max:70'])
                ->size('w-full'),
            Text::make(__('site::admin/resources/pages.meta.description'), 'description')
                ->translatable()
                ->hideFromIndex()
                ->rules(['max:200'])
                ->size('w-full'),
            Code::make(__('site::admin/resources/pages.meta.meta_tags'), 'meta_tags')
                ->translatable()
                ->hideFromIndex()
                ->size('w-full'),
            Code::make(__('site::admin/resources/pages.meta.scripts'), 'scripts')
                ->translatable()
                ->hideFromIndex()
                ->size('w-full'),
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
