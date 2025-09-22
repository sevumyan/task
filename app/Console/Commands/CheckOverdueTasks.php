<?php

namespace App\Console\Commands;

use App\Enums\Task\TaskStatus;
use App\Enums\TaskNotification\TaskNotificationType;
use App\Jobs\SendTaskNotificationJob;
use App\Models\Task;
use App\Models\TaskComment;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Throwable;

class CheckOverdueTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:check-overdue {--dry-run : Show what would be done without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for overdue tasks (in_progress status, created more than 7 days ago)';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        try {
            $sevenDaysAgo = Carbon::now()->subDays(7);

            $overdueTasks = Task::query()
                ->where('status', TaskStatus::IN_PROGRESS)
                ->where('created_at', '<', $sevenDaysAgo)
                ->get();

            $count = $overdueTasks->count();

            $this->info("Found {$count} overdue tasks.");

            if ($count === 0) {
                return;
            }

            if ($this->option('dry-run')) {
                $this->info('Dry run mode: No changes will be made.');
                foreach ($overdueTasks as $task) {
                    /**@var Task $task */
                    $this->line("- Task #{$task->id}: '{$task->title}' (created {$task->created_at->format('Y-m-d')})");
                }
                return;
            }

            $processed = 0;
            foreach ($overdueTasks as $task) {
                try {
                    /**@var Task $task */
                    $comment = TaskComment::query()->create([
                        'task_id' => $task->id,
                        'user_id' => $task->user_id,
                        'comment' => "Task is overdue! Created {$task->created_at->format('Y-m-d')}",
                    ]);

                    if (!$comment) {
                        $this->error("Failed to create comment for task #{$task->id}");
                        continue;
                    }

                    SendTaskNotificationJob::dispatch($task->id, TaskNotificationType::OVERDUE);

                    $this->line("Processed overdue task #{$task->id}: '{$task->title}'");
                    $processed++;

                } catch (Throwable $e) {
                    $this->error("Failed to process task #{$task->id}: {$e->getMessage()}");
                }
            }

            $this->info("Successfully processed {$processed} out of {$count} overdue tasks.");

        } catch (Throwable $e) {
            $this->error("Command failed: {$e->getMessage()}");
        }
    }
}
