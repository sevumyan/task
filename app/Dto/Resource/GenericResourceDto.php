<?php

namespace App\Dto\Resource;

use App\Dto\TransportDto;

/**
 * @template TValue
 * @property TValue $value
 */
final class GenericResourceDto extends TransportDto
{
    public function __construct(
        public readonly mixed $value
    ) {
    }
}
