<?php


namespace App\Enums;


class Gender extends BaseEnum
{
    const MALE = 1;
    const FEMALE = 2;

    protected static function labels(): array
    {
        return [
            self::MALE   => __('Male'),
            self::FEMALE => __('Female'),
        ];
    }
}
