<?php


namespace App\Enums;


class EmployeeType extends BaseEnum
{
    const FULL_TIME = 1;
    const PART_TIME = 2;

    protected static function labels(): array
    {
        return [
            self::FULL_TIME => __('Full Time'),
            self::PART_TIME => __('Part Time'),
        ];
    }

    public static function getColor($type): string
    {
        switch ($type) {
            case self::FULL_TIME:
                return 'success';
            case self::PART_TIME:
                return 'danger';
            default:
                return '';
        }
    }
}
