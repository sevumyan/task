<?php

namespace App\Exceptions\Task;

use App\Exceptions\BaseExceptionHandler;
use Symfony\Component\HttpFoundation\Response;

class TaskUpdateFailedException extends BaseExceptionHandler
{
    protected $code = Response::HTTP_UNPROCESSABLE_ENTITY;
    protected $message = 'Failed to update task';
    
    public string $scope = 'E_TASK_UPDATE';
    public string $textCode = 'E_TASK_UPDATE_FAILED';
    protected ?string $text = 'Task update failed due to invalid data or system error';
}