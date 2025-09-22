<?php

namespace App\Http\Services\Task\Command;

use App\Cqrs\Command\AbstractCommandHandler;
use App\Dto\DtoInterface;
use App\Dto\Resource\GenericResourceDto;
use App\Enums\Task\TaskStatus;
use App\Exceptions\Task\InvalidTaskStatusException;
use App\Exceptions\Task\TaskCommentCreationFailedException;
use App\Exceptions\Task\TaskNotFoundException;
use App\Exceptions\User\UserNotFoundException;
use App\Http\Services\Task\Command\Dto\CreateTaskCommentDto;
use App\Models\Task;
use App\Models\TaskComment;
use App\Models\User;
use InvalidArgumentException;
use Throwable;

final class CreateTaskCommentCommand extends AbstractCommandHandler
{
    /**
     * @throws TaskNotFoundException
     * @throws UserNotFoundException
     * @throws InvalidTaskStatusException
     * @throws TaskCommentCreationFailedException
     */
    public function handle(DtoInterface $dto): DtoInterface
    {
        if (!($dto instanceof CreateTaskCommentDto)) {
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

            if ($task->status === TaskStatus::CANCELLED->value) {
                throw new InvalidTaskStatusException();
            }

            $comment = TaskComment::query()->create([
                'task_id' => $dto->taskId,
                'user_id' => $dto->userId,
                'comment' => $dto->comment,
            ]);

            if (!$comment) {
                throw new TaskCommentCreationFailedException();
            }

            return new GenericResourceDto($comment->load('user'));

        } catch (TaskNotFoundException|UserNotFoundException|InvalidTaskStatusException $e) {
            throw $e;
        } catch (Throwable) {
            throw new TaskCommentCreationFailedException();
        }
    }
}
