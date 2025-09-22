<?php

namespace App\Http\Services\Task\Query;

use App\Cqrs\Query\AbstractQueryHandler;
use App\Dto\DtoInterface;
use App\Dto\Resource\GenericResourceDto;
use App\Http\Services\Task\Query\Dto\IndexTaskDto;
use App\Models\Task;
use InvalidArgumentException;

class IndexTaskQuery extends AbstractQueryHandler
{
    public function handle(DtoInterface $dto): DtoInterface
    {
        if (!($dto instanceof IndexTaskDto)) {
            throw new InvalidArgumentException('Invalid DTO type');
        }

        $query = Task::query()->with(['user'])
            ->orderBy('created_at', 'desc');

        if ($dto->status) {
            $query->where('status', $dto->status);
        }

        if ($dto->priority) {
            $query->where('priority', $dto->priority);
        }

        if ($dto->userId) {
            $query->where('user_id', $dto->userId);
        }

        $tasks = $query->paginate($dto->perPage, ['*'], 'page', $dto->page);

        return new GenericResourceDto($tasks);
    }
}
