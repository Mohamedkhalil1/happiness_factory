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

    public static function getColor($status): string
    {
        switch ($status) {
            case self::SINGLE:
                return 'success';
            case self::ENGAGED:
                return 'warning';
            case self::MARRIED:
                return 'danger';
            default:
                return '';
        }
    }
}
