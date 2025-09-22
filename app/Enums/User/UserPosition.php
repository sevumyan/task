<?php

namespace App\Enums\User;

enum UserPosition: string
{
    case MANAGER = 'manager';
    case DEVELOPER = 'developer';
    case TESTER = 'tester';
}
