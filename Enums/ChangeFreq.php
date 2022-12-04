<?php

namespace Modules\Site\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Contracts\LocalizedEnum;

/**
 * @method static static Always()
 * @method static static Hourly()
 * @method static static Daily()
 * @method static static Weekly()
 * @method static static Monthly()
 * @method static static Yearly()
 * @method static static Never()
 */
final class ChangeFreq extends Enum implements LocalizedEnum
{
    const Always    = 1;
    const Hourly    = 2;
    const Daily     = 3;
    const Weekly    = 4;
    const Monthly   = 5;
    const Yearly    = 6;
    const Never     = 0;
}