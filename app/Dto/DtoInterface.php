<?php

namespace App\Dto;

interface DtoInterface
{
    public function properties(): array;

    public function property(string $key): ?string;

}
