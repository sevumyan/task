<?php

namespace App\Exceptions\Task;

use App\Exceptions\BaseExceptionHandler;
use Symfony\Component\HttpFoundation\Response;

class TaskNotFoundException extends BaseExceptionHandler
{
    protected $code = Response::HTTP_NOT_FOUND;
    protected $message = 'Task not found';
}
