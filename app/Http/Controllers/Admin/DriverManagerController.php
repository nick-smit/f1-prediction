<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StoreDriverRequest;
use App\Http\Requests\UpdateDriverRequest;
use App\Models\Driver;
use App\Models\DriverContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Inertia\Inertia;

class DriverManagerController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Driver::with('contracts', 'contracts.team')
            ->whereLike('name', '%' . $request->query('s') . '%');

        if ($request->query('hide_inactive', true)) {
            $query->whereHas('contracts', function (Builder $builder): void {
                $builder->active();
            });
        }

        /** @var LengthAwarePaginator $drivers */
        $drivers = $query
            ->paginate(15)
            ->withQueryString()
            ->through(static function (Driver $driver): array {
                $contract = $driver->getCurrentContract();

                return  [
                    'id' => $driver->id,
                    'name' => $driver->name,
                    'number' => $driver->number,
                    'has_contract' => $contract instanceof DriverContract,
                    'current_team_id' => $contract?->team->id,
                    'current_team_name' => $contract?->team->name,
                    'current_contract_start' => $contract?->start_date,
                    'current_contract_end' => $contract?->end_date,
                ];
            });

        return Inertia::render(
            'Admin/Drivers/Index',
            [
                'drivers' => $drivers->toArray(),
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): void
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDriverRequest $request): void
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Driver $driver): void
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Driver $driver): void
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDriverRequest $request, Driver $driver): void
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Driver $driver): void
    {
        //
    }
}
