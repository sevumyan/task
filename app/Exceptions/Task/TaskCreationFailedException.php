<?php

namespace App\Exceptions\Task;

use App\Exceptions\BaseExceptionHandler;
use Symfony\Component\HttpFoundation\Response;

class TaskCreationFailedException extends BaseExceptionHandler
{
    protected $code = Response::HTTP_UNPROCESSABLE_ENTITY;
    protected $message = 'Failed to create task';
    
    public string $scope = 'E_TASK_CREATION';
    public string $textCode = 'E_TASK_CREATION_FAILED';
    protected ?string $text = 'Task creation failed due to invalid data or system error';
}