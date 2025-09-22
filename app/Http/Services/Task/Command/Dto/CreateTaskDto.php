<?php

namespace App\Http\Services\Task\Command\Dto;

use App\Dto\TransportDto;

final class CreateTaskDto extends TransportDto
{
    public function __construct(
       public string $title,
       public ?string $description = null,
       public ?string $userId = null,
       public ?string $status = null,
       public ?string $priority = null,
    ) {
    }
}
