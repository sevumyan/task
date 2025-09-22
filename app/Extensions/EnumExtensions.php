<?php

namespace App\Extensions;

abstract class EnumExtensions
{
    public static function values(array $cases): array
    {
        return array_column($cases, 'value');
    }
}
