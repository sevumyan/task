<?php

namespace App\Exceptions\Task;

use App\Exceptions\BaseExceptionHandler;
use Symfony\Component\HttpFoundation\Response;

class InvalidTaskStatusException extends BaseExceptionHandler
{
    protected $code = Response::HTTP_BAD_REQUEST;
    protected $message = 'Invalid task status operation';
    
    public string $scope = 'E_TASK_STATUS';
    public string $textCode = 'E_INVALID_TASK_STATUS';
    protected ?string $text = 'Cannot perform this operation due to current task status';
}