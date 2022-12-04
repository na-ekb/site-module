<?php

namespace Modules\Site\Entities;

use Illuminate\Database\Eloquent\Model;

use Spatie\Translatable\HasTranslations;

class PageMeta extends Model
{
    use HasTranslations;

    /**
     * {@inheritdoc}
     */
    protected $table = 'page_metas';

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'title',
        'small_desc',
        'description',
        'keywords',
        'meta_tags',
        'scripts',
        'sitemap',
        'changefreq',
        'priority'
    ];

    /**
     * The attributes that are translatable
     *
     * @var string[]
     */
    public $translatable = [
        'title',
        'small_desc',
        'description',
        'keywords',
        'meta_tags',
        'scripts'
    ];

    /**
     * Get the page of this meta.
     */
    public function pages()
    {
        return $this->hasMany(Page::class);
    }
}
