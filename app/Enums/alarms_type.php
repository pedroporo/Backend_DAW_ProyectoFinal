<?php

namespace App\Enums;

enum Alarms_type
{

    case medication;
    case special_alert;
    case emergency_followup;
    case bereavement;
    case hospital_discharge;
    case absence_suspension;
    case return_from_absence;
}
