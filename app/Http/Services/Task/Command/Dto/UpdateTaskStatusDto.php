<?php

namespace App\Http\Services\Task\Command\Dto;

use App\Dto\TransportDto;

final class UpdateTaskStatusDto extends TransportDto
{
    public function __construct(
        public int $taskId,
        public string $status,
        public int $userId,
    ) {
    }
}