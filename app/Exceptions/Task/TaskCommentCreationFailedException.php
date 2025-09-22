<?php

namespace App\Exceptions\Task;

use App\Exceptions\BaseExceptionHandler;
use Symfony\Component\HttpFoundation\Response;

class TaskCommentCreationFailedException extends BaseExceptionHandler
{
    protected $code = Response::HTTP_UNPROCESSABLE_ENTITY;
    protected $message = 'Failed to create task comment';
    
    public string $scope = 'E_TASK_COMMENT_CREATION';
    public string $textCode = 'E_TASK_COMMENT_CREATION_FAILED';
    protected ?string $text = 'Task comment creation failed due to invalid data or system error';
}