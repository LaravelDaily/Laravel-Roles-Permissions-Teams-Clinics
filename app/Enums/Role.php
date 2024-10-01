<?php

namespace App\Enums;

enum Role: string
{
    case Patient = 'patient';
    case Doctor = 'doctor';
    case Staff = 'staff';
    case ClinicAdmin = 'clinic-admin';
    case ClinicOwner = 'clinic-owner';
    case MasterAdmin = 'master-admin';

    public function label(): string
    {
        return match ($this) {
            self::Patient     => 'Patient',
            self::Doctor      => 'Doctor',
            self::Staff       => 'Staff',
            self::ClinicAdmin => 'Clinic Admin',
            self::ClinicOwner => 'Clinic Owner',
            self::MasterAdmin => 'Master Admin',
        };
    }
}
