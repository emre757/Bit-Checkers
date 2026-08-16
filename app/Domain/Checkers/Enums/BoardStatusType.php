<?php

namespace App\Domain\Checkers\Enums;

enum BoardStatusType: string
{
    case Active = 'active';
    case Inactive = 'inactive';
}
