<?php

namespace App\Http\Services\Task\Command\Dto;

use App\Dto\TransportDto;

final class CreateTaskCommentDto extends TransportDto
{
    public function __construct(
        public int $taskId,
        public string $comment,
        public int $userId,
    ) {
    }
}