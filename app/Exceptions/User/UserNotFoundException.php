<?php

namespace App\Exceptions\User;

use App\Exceptions\BaseExceptionHandler;
use Symfony\Component\HttpFoundation\Response;

class UserNotFoundException extends BaseExceptionHandler
{
    protected $code = Response::HTTP_NOT_FOUND;
    protected $message = 'User not found';
    
    public string $scope = 'E_USER';
    public string $textCode = 'E_USER_NOT_FOUND';
    protected ?string $text = 'The requested user could not be found';
}