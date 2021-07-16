<?php


namespace App\Enums;


class SocialStatus extends BaseEnum
{
    const SINGLE = 1;
    const ENGAGED = 2;
    const MARRIED = 3;


    protected static function labels(): array
    {
        return [
            self::SINGLE  => __('Single'),
            self::ENGAGED => __('Engaged'),
            self::MARRIED => __('Married'),
        ];
    }
}
