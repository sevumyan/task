<?php

namespace App\Dto\Resource;

use App\Dto\TransportDto;

final class BooleanIndicatorResourceDto extends TransportDto
{
    public function __construct(
        public readonly bool $result,
        public readonly ?string $operation = null
    ) {}

    public function success(): bool
    {
        return $this->result === true;
    }
}
