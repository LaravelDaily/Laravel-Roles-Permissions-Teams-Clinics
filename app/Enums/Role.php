<?php

namespace App\Enums;

enum Role: string
{
    case Patient = 'patient';
    case Doctor = 'doctor';
    case Staff = 'staff';
    case Admin = 'admin';
    case SuperAdmin = 'super-admin';
    case MasterAdmin = 'master-admin';

    public function label(): string
    {
        return match ($this) {
            self::Patient     => 'Patient',
            self::Doctor      => 'Doctor',
            self::Staff       => 'Staff',
            self::Admin       => 'Admin',
            self::SuperAdmin  => 'Super Admin',
            self::MasterAdmin => 'Master Admin',
        };
    }
}
