<?php

namespace App\Enums\TaskNotification;

enum TaskNotificationType: string
{
    case TASK_ASSIGNED = 'task_assigned';
    case STATUS_CHANGED = 'task_status_changed';
    case OVERDUE = 'task_overdue';
}
