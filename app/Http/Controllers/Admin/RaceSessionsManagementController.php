<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Models\Driver;
use App\Models\RaceSession;
use App\Models\SessionResult;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use RuntimeException;

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
                ->withCount('guesses')
                ->withExists('sessionResult')
                ->whereGuessable(true)
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
                    'guesses' => $session->guesses_count,
                    'has_results' => $session->getAttribute('session_result_exists'),
                ]),
            'action_required' => fn () => RaceSession::query()
                ->with('raceWeekend')
                ->select()
                ->selectRaw('(select count(1) from `guesses` where `guesses`.`score` IS NULL and `guesses`.`race_session_id` = `race_sessions`.`id`) as guesses_count')
                ->withExists('sessionResult')
                ->whereGuessable(true)
                ->whereRaw('session_end < NOW()')
                ->where(function (Builder $builder): void {
                    $builder->having('session_result_exists', false)
                        ->orHaving('guesses_count', '>', 0);
                })->get()
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
                'guesses' => $raceSession->guesses->count(),
                'has_results' => $raceSession->sessionResult instanceof SessionResult
            ],
            'action' => fn (): ?string => $this->getRequiredAction($raceSession, false),
            'results' => function () use ($raceSession): ?array {
                if ($raceSession->sessionResult === null) {
                    return null;
                }

                $drivers = Driver::query()
                    ->whereIn('id', [
                        $raceSession->sessionResult->p1_id,
                        $raceSession->sessionResult->p2_id,
                        $raceSession->sessionResult->p3_id,
                        $raceSession->sessionResult->p4_id,
                        $raceSession->sessionResult->p5_id,
                        $raceSession->sessionResult->p6_id,
                        $raceSession->sessionResult->p7_id,
                        $raceSession->sessionResult->p8_id,
                        $raceSession->sessionResult->p9_id,
                        $raceSession->sessionResult->p10_id,
                    ])->get();

                return [
                    'p1' => $drivers->firstWhere('id', $raceSession->sessionResult->p1_id),
                    'p2' => $drivers->firstWhere('id', $raceSession->sessionResult->p2_id),
                    'p3' => $drivers->firstWhere('id', $raceSession->sessionResult->p3_id),
                    'p4' => $drivers->firstWhere('id', $raceSession->sessionResult->p4_id),
                    'p5' => $drivers->firstWhere('id', $raceSession->sessionResult->p5_id),
                    'p6' => $drivers->firstWhere('id', $raceSession->sessionResult->p6_id),
                    'p7' => $drivers->firstWhere('id', $raceSession->sessionResult->p7_id),
                    'p8' => $drivers->firstWhere('id', $raceSession->sessionResult->p8_id),
                    'p9' => $drivers->firstWhere('id', $raceSession->sessionResult->p9_id),
                    'p10' => $drivers->firstWhere('id', $raceSession->sessionResult->p10_id),
                ];
            }]);
    }

    private function getRequiredAction(RaceSession $session, bool $throw = true): ?string
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

        if (!$session->hasAttribute('guesses_count')) {
            $session->setAttribute('guesses_count', $session->guesses->whereNull('score')->count());
        }

        if ($session->guesses_count > 0) {
            return 'calculate-scores';
        }

        if ($throw) {
            // @codeCoverageIgnoreStart
            throw new RuntimeException('Could not determine required action for session ' . $session->id);
            // @codeCoverageIgnoreEnd
        }

        return null;
    }
}
