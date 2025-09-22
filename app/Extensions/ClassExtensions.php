<?php

namespace App\Extensions;

final class ClassExtensions
{
    /**
     * @param string $class
     * @param string $of
     * @return bool
     */
    public static function children(string $class, string $of): bool
    {
        return self::exists($class)
            && self::exists($of)
            && is_a($class, $of, true);
    }

    /**
     * @param string $class
     * @param string $method
     * @return bool
     */
    public static function methodExistsIn(string $class, string $method): bool
    {
        return ! empty($class)
            && ! empty($method)
            && self::exists($class)
            && method_exists($class, $method);
    }

    /**
     * @param string $class
     * @return bool
     */
    public static function exists(string $class): bool
    {
        return ! empty($class)
            && class_exists($class);
    }
}
