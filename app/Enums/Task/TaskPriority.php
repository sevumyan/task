<?php

namespace App\Enums\Task;

enum TaskPriority: string
{
    case HIGH = 'high';
    case NORMAL = 'normal';
    case LOW = 'low';
}
