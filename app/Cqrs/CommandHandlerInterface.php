<?php

namespace App\Cqrs;

use App\Dto\DtoInterface;

interface CommandHandlerInterface
{
    public function handle(DtoInterface $dto): mixed;
}
