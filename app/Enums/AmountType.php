<?php


namespace App\Enums;


class AmountType extends BaseEnum
{
    const NORMAL = 1;
    const INCREASE = 2;
    const DECREASE = 3;

    protected static function labels(): array
    {
        return [
            self::NORMAL   => __('Normal'),
            self::INCREASE => __('Increase'),
            self::DECREASE => __('Decrease'),
        ];
    }

    public static function getColor($type): string
    {
        switch ($type) {
            case self::NORMAL:
                return 'secondary';
            case self::INCREASE:
                return 'success';
            case self::DECREASE:
                return 'danger';
            default:
                return '';
        }
    }
}
