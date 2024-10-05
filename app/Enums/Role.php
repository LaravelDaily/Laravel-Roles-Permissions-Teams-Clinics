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
}
