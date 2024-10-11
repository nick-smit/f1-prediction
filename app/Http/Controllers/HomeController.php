<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\RaceSession;
use Inertia\Response;
use Inertia\ResponseFactory;

class HomeController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(ResponseFactory $responseFactory): Response
    {
        $nextSession = RaceSession::query()
            ->with('raceWeekend')
            ->whereRaw('`session_start` > NOW()')
            ->orderBy('session_start')
            ->first();

        return $responseFactory->render('Home/Home', [
            'next_event' => $nextSession instanceof RaceSession ? [
                'id' => $nextSession->id,
                'race_weekend_name' => $nextSession->raceWeekend->name,
                'type' => $nextSession->type,
                'session_start' => $nextSession->session_start,
            ] : null,
        ]);
    }
}
