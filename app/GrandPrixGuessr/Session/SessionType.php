<?php

declare(strict_types=1);

namespace App\GrandPrixGuessr\Session;

enum SessionType: string
{
    case Practice = 'practice';
    case SprintQualification = 'sprint_qualification';
    case SprintRace = 'sprint_race';
    case Qualification = 'qualification';
    case Race = 'race';
}
