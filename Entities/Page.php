<?php

namespace Modules\Site\Entities;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Blade;
use Spatie\Translatable\HasTranslations;

class Page extends Model
{
    use HasTranslations;

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'title',
        'slug',
        'view',
        'active',
        'order',
        'content',
        'page_meta_id'
    ];

    /**
     * The attributes that are translatable
     *
     * @var string[]
     */
    public $translatable = [
        'title', 'content'
    ];

    /**
     * Get the meta of page.
     */
    public function pageMeta()
    {
        return $this->belongsTo(PageMeta::class);
    }
}
