<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Actions\RaceSession\CalculateScores;
use App\Actions\RaceSession\ImportSessionResults;
use App\GrandPrixGuessr\Data\Scraper\StatsF1\SessionResultNotFoundException;
use App\Http\Resources\SessionResultResource;
use App\Models\RaceSession;
use App\Models\SessionResult;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\ResponseFactory;
use Inertia\Inertia;
use Inertia\Response;

class RaceSessionsManagementController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $yearFilter = $request->query('year', Carbon::now()->year);

        return Inertia::render('Admin/RaceSessions/Index', [
            'race_sessions' => fn () => RaceSession::query()
                ->with(['raceWeekend'])
                ->withCount('predictions')
                ->withExists('sessionResult')
                ->whereHas('raceWeekend', function (Builder $builder) use ($yearFilter): void {
                    $builder->whereRaw('YEAR(start_date) = ?', [$yearFilter]);
                })
                ->orderBy('session_start')
                ->paginate(15)
                ->withQueryString()
                ->through(fn (RaceSession $session): array => [
                    'id' => $session->id,
                    'race_weekend_name' => $session->raceWeekend->name,
                    'type' => $session->type->value,
                    'session_start' => $session->session_start,
                    'session_end' => $session->session_end,
                    'predictions' => $session->predictions_count,
                    'has_results' => $session->getAttribute('session_result_exists'),
                ]),
            'action_required' => fn () => RaceSession::query()
                ->with('raceWeekend')
                ->select()
                ->selectRaw('(select count(1) from `predictions` where `predictions`.`score` IS NULL and `predictions`.`race_session_id` = `race_sessions`.`id`) as predictions_count')
                ->withExists('sessionResult')
                ->whereRaw('session_end < NOW()')
                ->having('session_result_exists', '=', 0)
                ->orHaving('predictions_count', '>', 0)->get()
                ->map(fn (RaceSession $session): array => [
                    'id' => $session->id,
                    'race_weekend_name' => $session->raceWeekend->name,
                    'type' => $session->type->value,
                    'action' => $this->getRequiredAction($session),
                ])
        ]);
    }

    public function show(RaceSession $raceSession): Response
    {
        return Inertia::render('Admin/RaceSessions/Show', [
            'race_session' => fn (): array => [
                'id' => $raceSession->id,
                'race_weekend_name' => $raceSession->raceWeekend->name,
                'type' => $raceSession->type->value,
                'session_start' => $raceSession->session_start,
                'session_end' => $raceSession->session_end,
                'predictions' => $raceSession->predictions->count(),
                'has_results' => $raceSession->sessionResult instanceof SessionResult
            ],
            'action' => fn (): ?string => $this->getRequiredAction($raceSession),
            'results' => function () use ($raceSession): ?SessionResultResource {
                if ($raceSession->sessionResult === null) {
                    return null;
                }

                return new SessionResultResource($raceSession->sessionResult);
            }]);
    }

    public function importResults(RaceSession $raceSession, ImportSessionResults $action, ResponseFactory $responseFactory): \Illuminate\Http\Response|JsonResponse
    {
        try {
            $action->handle($raceSession);
        } catch (SessionResultNotFoundException $sessionResultNotFoundException) {
            return $responseFactory->json(['message' => $sessionResultNotFoundException->getMessage()], \Illuminate\Http\Response::HTTP_NOT_FOUND);
        }

        return $responseFactory->noContent();
    }

    public function calculateScores(RaceSession $raceSession, CalculateScores $action, ResponseFactory $responseFactory): \Illuminate\Http\Response
    {
        $action->handle($raceSession);

        return $responseFactory->noContent();
    }

    private function getRequiredAction(RaceSession $session): ?string
    {
        if ($session->session_end > Carbon::now()) {
            return null;
        }

        if (!$session->hasAttribute('session_result_exists')) {
            $session->setAttribute('session_result_exists', $session->sessionResult !== null);
        }

        if ($session->getAttribute('session_result_exists') === false) {
            return 'import-results';
        }

        if (!$session->hasAttribute('predictions_count')) {
            $session->setAttribute('predictions_count', $session->predictions->whereNull('score')->count());
        }

        if ($session->predictions_count > 0) {
            return 'calculate-scores';
        }

        return null;
    }
}
