<?php

namespace App\Enums;

enum Role: string
{
    case User = 'user';
    case Admin = 'admin';
    case SuperAdmin = 'super-admin';
    case MasterAdmin = 'master-admin';

    public function label(): string
    {
        return match ($this) {
            self::User        => 'User',
            self::Admin       => 'Admin',
            self::SuperAdmin  => 'Super Admin',
            self::MasterAdmin => 'Master Admin',
        };
    }
}
