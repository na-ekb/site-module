<?php

namespace Modules\Site\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

use Spatie\Translatable\HasTranslations;

class Jft extends Model
{
    //use HasTranslations;

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'header',
        'quote',
        'from'
    ];

    /**
     * The attributes that are translatable
     *
     * @var string[]
     */
    public $translatable = [
        'header', 'quote', 'from'
    ];

    /**
     * Scope for get today JFT
     *
     * @param  Builder $query
     * @return void
     */
    public static function scopeToday(Builder $query) :void {
        $today = Carbon::today()->setTimezone(config('Site.site_jft_timezone'));
        $query->whereMonth('created_at', $today->month)->whereDay('created_at', $today->day);
    }
}
