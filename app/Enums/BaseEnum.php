<?php

namespace App\Enums;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\In;

abstract class BaseEnum
{
    protected static abstract function labels(): array;

    public static function names(): array
    {
        static $names;

        if (!$names) {
            $names = static::labels();
        }

        return $names;
    }

    public static function keys(): array
    {
        static $keys;

        if (!$keys) {
            $keys = array_keys(static::names());
        }

        return $keys;
    }

    /**
     * @param string|int $status
     *
     * @return string|null
     */
    public static function name($status): ?string
    {
        return static::names()[$status] ?? null;
    }

    public static function valid(): In
    {
        return Rule::in(static::keys());
    }

    public static function keyValue(): array
    {
        $array = [];
        foreach (static::names() as $key => $value) {
            $array[$key]['id'] = $key;
            $array[$key]['name'] = $value;
        }
        return $array;
    }
}
