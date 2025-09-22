<?php

namespace App\Cqrs\Command;

use App\Cqrs\CommandHandlerInterface;
use App\Dto\DtoInterface;

abstract class AbstractCommandHandler implements CommandHandlerInterface
{
    /*** @return mixed */
    public static function execute(DtoInterface $dto): DtoInterface
    {
        /** @var AbstractCommandHandler $command */
        $command = (app(static::class));

        return $command->handle($dto);
    }
}
