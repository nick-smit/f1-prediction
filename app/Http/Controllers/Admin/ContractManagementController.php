<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ContractRequest;
use App\Models\Driver;
use App\Models\DriverContract;
use App\Models\Team;
use Illuminate\Routing\ResponseFactory;
use Inertia\Inertia;

class ContractManagementController
{
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render(
            'Admin/Contracts/Create',
            [
                'drivers' => Driver::query()->get(),
                'teams' => Team::query()->get(),
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ContractRequest $request, ResponseFactory $responseFactory)
    {
        $contract = new DriverContract($request->safe()->only(['start_date', 'end_date']));

        $contract->driver_id = $request->post('driver');
        $contract->team_id = $request->post('team');

        $contract->save();

        return $responseFactory->redirectToRoute('admin.drivers.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DriverContract $contract)
    {
        return Inertia::render(
            'Admin/Contracts/Edit',
            [
                'contract' => $contract,
                'drivers' => Driver::query()->get(),
                'teams' => Team::query()->get(),
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ContractRequest $request, DriverContract $contract, ResponseFactory $responseFactory)
    {
        $contract->fill($request->safe()->only(['start_date', 'end_date']));

        $contract->driver_id = $request->post('driver');
        $contract->team_id = $request->post('team');

        $contract->save();

        return $responseFactory->redirectToRoute('admin.drivers.index');
    }
}
