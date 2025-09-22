<?php

namespace App\Http\Services\Task\Query\Dto;

use App\Dto\TransportDto;

final class IndexTaskDto extends TransportDto
{
    public function __construct(
       public int $page ,
       public int $perPage,
       public ?string $status = null,
       public ?string $priority = null,
       public ?int $userId = null,
    ) {
    }
}
