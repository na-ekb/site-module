<?php

namespace Modules\Site\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

use Spatie\Translatable\HasTranslations;

class Jft extends Model
{
    use HasTranslations;

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
     * @return self
     */
    public static function today() :self {
        $today = Carbon::today();
        return self::whereMonth('created_at', $today->month)->whereDay('created_at', $today->day)->first();
    }
}
