<?php

namespace Modules\Site\Nova;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Number;

use App\Nova\Resources\Resource;

class Page extends Resource
{
    /**
     * {@inheritdoc}
     */
    public static $model = \Modules\Site\Entities\Page::class;

    /**
     * {@inheritdoc}
     */
    public static $with = ['pageMeta'];

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
        return __('site::admin/resources/pages.pages');
    }

    /**
     * {@inheritdoc}
     */
    public static function singularLabel()
    {
        return __('site::admin/resources/pages.page');
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
                ->rules(['max:100'])
                ->size('w-full'),
            Slug::make(__('site::admin/resources/pages.fields.slug'), 'slug')
                ->from('title')
                ->sortable()
                ->rules(['max:50'])
                ->size('w-1/3'),
            Select::make(__('site::admin/resources/pages.fields.view'), 'view')
                ->options(
                    collect(File::files(module_path('Site', 'Resources/views')))
                        ->mapWithKeys(function ($file) {
                            $file = basename($file->getRealPath(), '.blade.php');
                            return [$file => $file];
                        })
                )
                ->hideFromIndex()
                ->sortable()
                ->size('w-1/3'),
            Number::make(__('site::admin/resources/pages.fields.order'), 'order')
                ->sortable()
                ->rules(['max:100'])
                ->size('w-1/6'),
            Boolean::make(__('site::admin/resources/pages.fields.active'), 'active')
                ->sortable()
                ->size('w-1/6'),
            Trix::make(__('site::admin/resources/pages.fields.content'), 'content')
                ->translatable()
                ->hideFromIndex()
                ->size('w-full'),

            BelongsTo::make(__('site::admin/resources/pages.meta.meta'), 'pageMeta', PageMeta::class)
                ->showCreateRelationButton()
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
