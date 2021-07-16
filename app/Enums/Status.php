<?php


namespace App\Enums;


class Status extends BaseEnum
{
    const PROCESSING = 1;
    const SUCCESS = 2;
    const FAILED = 3;

    protected static function labels(): array
    {
        return [
            self::PROCESSING => __('Processing'),
            self::SUCCESS       => __('Success'),
            self::FAILED    => __('Failed'),
        ];
    }

    public static function getColor($status): string
    {
        switch ($status) {
            case self::PROCESSING:
                return 'secondary';
            case self::SUCCESS:
                return 'success';
            case self::FAILED:
                return 'danger';
            default:
                return '';
        }
    }
}
