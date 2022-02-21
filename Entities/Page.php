<?php

namespace Modules\Site\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Spatie\Sitemap\Tags\Url;
use Spatie\Translatable\HasTranslations;
use Spatie\ResponseCache\Facades\ResponseCache;
use Spatie\Sitemap\Contracts\Sitemapable;

class Page extends Model implements Sitemapable
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
     * @return BelongsTo
     */
    public function pageMeta(): BelongsTo
    {
        return $this->belongsTo(PageMeta::class);
    }

    /**
     * Response cache clearing
     *
     * @return void
     */
    public static function bootClearsResponseCache(): void
    {
        self::created(function () {
            ResponseCache::forget("/{$this->slug}");
        });

        self::updated(function () {
            ResponseCache::forget("/{$this->slug}");
        });
    }

    /**
     * @return Url|string|array
     */
    public function toSitemapTag(): Url|string|array
    {
        return route('pages', ['slug' => $this->slug]);
    }
}
