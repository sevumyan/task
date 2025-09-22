<?php

namespace App\Cqrs;

use App\Dto\DtoInterface;

interface QueryHandlerInterface
{
    public function handle(DtoInterface $dto): DtoInterface;
}
