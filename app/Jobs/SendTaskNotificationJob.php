<?php

namespace App\Jobs;

use App\Enums\TaskNotification\TaskNotificationType;
use App\Enums\User\UserPosition;
use App\Exceptions\Task\TaskNotFoundException;
use App\Models\Task;
use App\Models\TaskNotification;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class SendTaskNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly int $taskId,
        private readonly TaskNotificationType $notificationType,
    ) {
    }

    /** @throws TaskNotFoundException|Throwable */
    public function handle(): void
    {
        try {
            $task = Task::query()->with('user')->find($this->taskId);
            if (!$task) {
                throw new TaskNotFoundException();
            }

            $managers = User::query()->where('position', UserPosition::MANAGER)->get();

            $message = $this->generateMessage($task, $this->notificationType);

            foreach ($managers as $manager) {
                /** @var TaskNotification $notification */
                /**@var User $manager */
                $notification = TaskNotification::query()->create([
                    'user_id' => $manager->id,
                    'task_id' => $this->taskId,
                    'message' => $message,
                    'type' => $this->notificationType,
                ]);

                if ($notification) {
                    Log::info("Notification sent to manager {$manager->name}: {$message}");
                } else {
                    Log::error("Failed to create notification for manager {$manager->name}");
                }
            }

        } catch (TaskNotFoundException|Throwable $e) {
            Log::error("SendTaskNotificationJob failed: {$e->getMessage()}");
            throw $e;
        }
    }

    private function generateMessage(Task $task, TaskNotificationType $type): string
    {
        return match ($type) {
            TaskNotificationType::TASK_ASSIGNED => "High priority task '{$task->title}' has been assigned to {$task->user->name}",
            TaskNotificationType::STATUS_CHANGED => "Task '{$task->title}' status changed to {$task->status}",
            TaskNotificationType::OVERDUE => "Task '{$task->title}' is overdue (created {$task->created_at->format('Y-m-d')})",
        };
    }
}
