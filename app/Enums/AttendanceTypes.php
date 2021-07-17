<?php


namespace App\Enums;


class AttendanceTypes extends BaseEnum
{
    const ATTENDED = 1;
    const ABSENT = 2;

    protected static function labels(): array
    {
        return [
            self::ATTENDED => __('Attended'),
            self::ABSENT   => __('Absent'),
        ];
    }

    public static function getColor($type): string
    {
        switch ($type) {
            case self::ATTENDED:
                return 'success';
            case self::ABSENT:
                return 'danger';
            default:
                return '';
        }
    }
}
