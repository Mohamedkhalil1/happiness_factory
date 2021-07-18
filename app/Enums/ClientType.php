<?php


namespace App\Enums;


class ClientType extends BaseEnum
{
    const NORMAL = 1;
    const VIP = 2;

    protected static function labels(): array
    {
        return [
            self::NORMAL => __('Normal'),
            self::VIP => __('VIP'),
        ];
    }

    public static function getColor($type): string
    {
        switch ($type) {
            case self::NORMAL:
                return 'secondary';
            case self::VIP:
                return 'primary';
            default:
                return '';
        }
    }
}
