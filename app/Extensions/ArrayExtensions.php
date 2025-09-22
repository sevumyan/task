<?php

namespace App\Extensions;

use Illuminate\Support\Str;

final class ArrayExtensions
{
    /**
     * @param array|object $arrOrObject
     * @return array
     */
    public static function transformToSnake(array|object $arrOrObject): array
    {
        return self::transformArrayKeys($arrOrObject, Str::snake(...));
    }

    /**
     * @param array|object $arrOrObject
     * @param callable $func
     * @return array
     */
    private static function transformArrayKeys(array|object $arrOrObject, callable $func): array
    {
        $arr = [];
        foreach ($arrOrObject as $key => $value) {
            $arr[$func($key)] = $value;
        }

        return $arr;
    }

    /**
     * @param array|object $arrOrObject
     * @return array
     */
    public static function transformToCamelCase(array|object $arrOrObject): array
    {
        return self::transformArrayKeys($arrOrObject, Str::camel(...));
    }

    /**
     * @param array $a
     * @param array $b
     * @param mixed $key
     * @return array
     */
    public static function diff(array $a, array $b, mixed $key): array
    {
        $comparer = function() use ($a, $b, $key) {
            return $a[$key] - $b[$key];
        };

        return array_udiff($a, $b, $comparer);
    }
}

