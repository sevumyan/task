<?php

namespace App\Dto;

use App\Extensions\ArrayExtensions;
use Illuminate\Contracts\Support\Arrayable;

abstract class AbstractDto implements Arrayable, DtoInterface
{
    public function property(string $key): ?string
    {
        if (property_exists($this, $key)) {
            return $this->{$key};
        }

        return null;
    }

    public function properties(): array
    {
        return array_filter(
            ArrayExtensions::transformToSnake($this),
            fn ($value) => $value !== null
        );
    }

    public function toArray(): array
    {
        return $this->properties();
    }

    public static function fromArray(array $data): static
    {
        return new static(...ArrayExtensions::transformToCamelCase($data));
    }
}
