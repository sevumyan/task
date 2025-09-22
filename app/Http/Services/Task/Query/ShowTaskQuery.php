<?php

namespace App\Http\Services\Task\Query;

use App\Cqrs\Query\AbstractQueryHandler;
use App\Dto\DtoInterface;
use App\Dto\Resource\GenericResourceDto;
use App\Exceptions\Task\TaskNotFoundException;
use App\Http\Services\Task\Query\Dto\ShowTaskDto;
use App\Models\Task;
use InvalidArgumentException;
use Throwable;

class ShowTaskQuery extends AbstractQueryHandler
{
    /**
     * @throws TaskNotFoundException
     */
    public function handle(DtoInterface $dto): DtoInterface
    {
        if (!($dto instanceof ShowTaskDto)) {
            throw new InvalidArgumentException();
        }

        $task = Task::query()
            ->with(['user', 'comments.user'])
            ->find($dto->taskId);

        if (!$task) {
            throw new TaskNotFoundException();
        }

        return new GenericResourceDto($task);

    }
}
