<?php

namespace Modules\Site\Entities;

use Illuminate\Database\Eloquent\Model;

use Spatie\Translatable\HasTranslations;
use Spatie\ResponseCache\Facades\ResponseCache;

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

    /**
     * Response cache clearing
     *
     * @return void
     */
    public static function bootClearsResponseCache()
    {
        self::created(function () {
            ResponseCache::forget("/{$this->slug}");
        });

        self::updated(function () {
            ResponseCache::forget("/{$this->slug}");
        });
    }
}
