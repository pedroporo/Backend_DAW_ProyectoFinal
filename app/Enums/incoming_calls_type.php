<?php

namespace App\Enums;

enum Incoming_calls_type
{
    case social_emergency;
    case medical_emergency;
    case loneliness_crisis;
    case unanswered_alarm;
    case absence_notification;
    case data_update;
    case accidental;
    case info_request;
    case complaint;
    case social_call;
    case medical_appointment;
    case other;
}
