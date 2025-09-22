<?php

namespace App\Http\Services\Task\Query\Dto;

use App\Dto\TransportDto;

final class ShowTaskDto extends TransportDto
{
    public function __construct(
        public int $taskId,
    ) {
    }
}