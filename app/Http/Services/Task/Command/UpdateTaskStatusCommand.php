<?php

namespace App\Http\Services\Task\Command;

use App\Cqrs\Command\AbstractCommandHandler;
use App\Dto\DtoInterface;
use App\Dto\Resource\GenericResourceDto;
use App\Enums\Task\TaskStatus;
use App\Enums\TaskNotification\TaskNotificationType;
use App\Exceptions\Task\TaskNotFoundException;
use App\Exceptions\Task\TaskUpdateFailedException;
use App\Exceptions\User\UserNotFoundException;
use App\Http\Services\Task\Command\Dto\UpdateTaskStatusDto;
use App\Jobs\SendTaskNotificationJob;
use App\Models\Task;
use App\Models\TaskComment;
use App\Models\User;
use InvalidArgumentException;
use Throwable;

final class UpdateTaskStatusCommand extends AbstractCommandHandler
{
    /**
     * @throws TaskNotFoundException
     * @throws UserNotFoundException
     * @throws TaskUpdateFailedException
     */
    public function handle(DtoInterface $dto): DtoInterface
    {
        if (!($dto instanceof UpdateTaskStatusDto)) {
            throw new InvalidArgumentException();
        }

        try {
            $task = Task::query()->find($dto->taskId);
            if (!$task) {
                throw new TaskNotFoundException();
            }

            $user = User::query()->find($dto->userId);
            if (!$user) {
                throw new UserNotFoundException();
            }

            $updated = $task->update(['status' => $dto->status]);
            if (!$updated) {
                throw new TaskUpdateFailedException();
            }

            if ($dto->status === TaskStatus::CANCELLED->value) {
                $comment = TaskComment::query()->create([
                    'task_id' => $task->id,
                    'user_id' => $dto->userId,
                    'comment' => "Task completed by {$user->name}",
                ]);

                if (!$comment) {
                    throw new TaskUpdateFailedException();
                }
            }

            SendTaskNotificationJob::dispatch($task->id, TaskNotificationType::STATUS_CHANGED);

            return new GenericResourceDto($task->fresh());

        } catch (TaskNotFoundException|UserNotFoundException $e) {
            throw $e;
        } catch (Throwable) {
            throw new TaskUpdateFailedException();
        }
    }
}
