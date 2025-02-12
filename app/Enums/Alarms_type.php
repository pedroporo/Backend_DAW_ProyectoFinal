<?php

namespace App\Enums;

enum Alarms_type: string
{

    case medication='medication';
    case special_alert='special_alert';
    case emergency_followup='emergency_followup';
    case bereavement='bereavement';
    case hospital_discharge='hospital_discharge';
    case absence_suspension='absence_suspension';
    case return_from_absence='return_from_absence';
}
