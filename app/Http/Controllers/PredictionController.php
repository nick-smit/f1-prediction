<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Prediction\StorePrediction;
use App\GrandPrixGuessr\Session\SessionType;
use App\Http\Requests\Prediction\PredictionRequest;
use App\Models\Driver;
use App\Models\Prediction;
use App\Models\RaceSession;
use App\Models\RaceWeekend;
use Illuminate\Auth\AuthManager;
use Inertia\Inertia;
use Inertia\Response;

class PredictionController
{
    public function index(AuthManager $authManager): Response
    {
        /** @var RaceWeekend|null $event */
        $event = RaceWeekend::query()
            ->select(['race_weekends.id', 'race_weekends.name'])
            ->with('raceSessions')
            ->join('race_sessions', 'race_weekend_id', 'race_weekends.id')
            ->whereRaw('session_start > NOW()')
            ->orderBy('session_start')
            ->first();

        if ($event instanceof RaceWeekend) {
            $drivers = Driver::query()
                ->whereHas('contracts', fn ($builder) => $builder->active($event->raceSessions->first()->session_start))
                ->get();

            $qualification = $event->raceSessions
                ->firstWhere('type', SessionType::Qualification);

            $predictions = Prediction::query()
                ->whereUserId($authManager->user()->id)
                ->whereHas('raceSession', fn ($builder) => $builder->where('race_weekend_id', $event->id))
                ->get();

            $race = $event->raceSessions
                ->firstWhere('type', SessionType::Race);
        }

        return Inertia::render('Predict/Predict', [
            'event' => $event instanceof RaceWeekend ? [
                'name' => $event->name,
                'qualification' => [
                    ...$qualification->only(['id', 'session_start']),
                    'prediction' => $predictions
                        ->firstWhere('race_session_id', $qualification->id)
                        ?->getDrivers() ?? []
                ],
                'race' => [
                    ...$race->only(['id', 'session_start']),
                    'prediction' => $predictions
                        ->firstWhere('race_session_id', $race->id)
                        ?->getDrivers() ?? [],
                ],
            ] : null,
            'drivers' => $drivers ?? null,
        ]);
    }

    public function store(RaceSession $raceSession, StorePrediction $action, PredictionRequest $request): void
    {
        $action->handle(
            $request->user(),
            $raceSession,
            $request->get('prediction')
        );
    }
}
