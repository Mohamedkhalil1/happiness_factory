<?php


namespace App\Enums;


class OrderStatus extends BaseEnum
{
    const PENDING = 1;
    const IN_PROGRESS = 2;
    const DONE = 3;

    protected static function labels(): array
    {
        return [
            self::PENDING     => __('Pending'),
            self::IN_PROGRESS => __('In Progress'),
            self::DONE        => __('Done'),
        ];
    }

    public static function getColor($type): string
    {
        switch ($type) {
            case self::PENDING:
                return 'warning';
            case self::IN_PROGRESS:
                return 'secondary';
            case self::DONE:
                return 'success';
            default:
                return '';
        }
    }
}
