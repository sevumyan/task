<?php

namespace App\Http\Services\Task\Command;

use App\Cqrs\Command\AbstractCommandHandler;
use App\Dto\DtoInterface;
use App\Dto\Resource\GenericResourceDto;
use App\Enums\Task\TaskPriority;
use App\Enums\Task\TaskStatus;
use App\Enums\TaskNotification\TaskNotificationType;
use App\Enums\User\UserPosition;
use App\Exceptions\Task\TaskCreationFailedException;
use App\Exceptions\User\ManagerNotFoundException;
use App\Exceptions\User\UserNotFoundException;
use App\Http\Services\Task\Command\Dto\CreateTaskDto;
use App\Jobs\SendTaskNotificationJob;
use App\Models\Task;
use App\Models\User;
use InvalidArgumentException;

final class CreateTaskCommand extends AbstractCommandHandler
{
    /**
     * @throws TaskCreationFailedException
     * @throws UserNotFoundException
     * @throws ManagerNotFoundException
     */
    public function handle(DtoInterface $dto): DtoInterface
    {
        if (!($dto instanceof CreateTaskDto)) {
            throw new InvalidArgumentException();
        }

        try {
            $userId = $dto->userId;
            if ($userId) {
                $user = User::query()->find($userId);
                if (!$user) {
                    throw new UserNotFoundException();
                }
            } else {
                $manager = User::query()->where('position', UserPosition::MANAGER)->first();
                if (!$manager) {
                    throw new ManagerNotFoundException();
                }
                $userId = $manager->id;
            }

            $status = TaskStatus::NEW;
            if ($dto->priority === TaskPriority::HIGH->value) {
                $status = TaskStatus::IN_PROGRESS;
            }

            $task = Task::query()->create([
                'title' => $dto->title,
                'description' => $dto->description,
                'user_id' => $userId,
                'status' => $status,
                'priority' => $dto->priority ?? TaskPriority::NORMAL->value,
            ]);

            if (!$task) {
                throw new TaskCreationFailedException();
            }

            if ($dto->priority === TaskPriority::HIGH->value) {
                SendTaskNotificationJob::dispatch($task->id, TaskNotificationType::TASK_ASSIGNED);
            }

            return new GenericResourceDto($task);

        } catch (UserNotFoundException|ManagerNotFoundException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new TaskCreationFailedException('Task creation failed: ' . $e->getMessage());
        }
    }
}
