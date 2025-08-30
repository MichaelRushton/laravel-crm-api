<?php

namespace App\Enums;

enum UserRole: string
{
    case Administrator = 'administrator';
    case User = 'user';

    public function name(): string
    {
        return ucfirst($this->value);
    }
}
