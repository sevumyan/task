<?php

namespace App\Exceptions\User;

use App\Exceptions\BaseExceptionHandler;
use Symfony\Component\HttpFoundation\Response;

class ManagerNotFoundException extends BaseExceptionHandler
{
    protected $code = Response::HTTP_UNPROCESSABLE_ENTITY;
    protected $message = 'No manager available for task assignment';
    
    public string $scope = 'E_MANAGER';
    public string $textCode = 'E_MANAGER_NOT_FOUND';
    protected ?string $text = 'No manager found in the system for automatic task assignment';
}