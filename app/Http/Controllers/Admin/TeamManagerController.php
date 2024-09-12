<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\TeamRequest;
use App\Models\Team;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\ResponseFactory;
use Inertia\Inertia;
use Inertia\Response;

class TeamManagerController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $query = Team::with('contracts', 'contracts.driver')
            ->whereLike('name', '%' . $request->query('s') . '%');

        $teams = $query->paginate(15)
            ->withQueryString()
            ->through(static fn(Team $team): array => [
                'id' => $team->id,
                'name' => $team->name,
            ]);

        return Inertia::render('Admin/Teams/Index', [
            'teams' => $teams
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('Admin/Teams/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TeamRequest $request, ResponseFactory $responseFactory): RedirectResponse
    {
        $driver = new Team($request->validated());
        $driver->save();

        return $responseFactory->redirectToRoute('admin.teams.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Team $team): Response
    {
        return Inertia::render('Admin/Teams/Edit', [
            'team' => $team,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TeamRequest $request, Team $team, ResponseFactory $responseFactory): RedirectResponse
    {
        $team->update($request->validated());

        return $responseFactory->redirectToRoute('admin.teams.index');
    }
}
