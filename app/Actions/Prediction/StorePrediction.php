<?php

declare(strict_types=1);

namespace App\Actions\Prediction;

use App\Models\Prediction;
use App\Models\RaceSession;
use App\Models\User;

class StorePrediction
{
    /**
     * Execute the action.
     */
    public function handle(User $user, RaceSession $raceSession, iterable $prediction): void
    {
        $predictionModel = Prediction::query()->firstOrNew([
            'user_id' => $user->id,
            'race_session_id' => $raceSession->id,
        ]);

        foreach ($prediction as $index => $driverId) {
            $property = 'p' . $index + 1 . '_id';
            $predictionModel->$property = $driverId;
        }

        $predictionModel->save();
    }
}
