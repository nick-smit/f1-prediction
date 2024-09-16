<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Admin;

use App\Models\Driver;
use App\Models\DriverContract;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use Tests\Feature\Rules\UniqueDriverContractTest;
use Tests\TestCase;

final class ContractManagementControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_admin_can_view_the_create_contract_form(): void
    {
        $user = User::factory()->admin()->create();

        Team::factory()->count(3)->create();
        Driver::factory()->count(3)->create();

        $response = $this->actingAs($user)->get(route('admin.contracts.create'));

        $response->assertOk();
        $response->assertInertia(function (AssertableInertia $assertableInertia): void {
            $assertableInertia->component('Admin/Contracts/Create');
            $assertableInertia->has('teams', 3);
            $assertableInertia->has('drivers', 3);
        });
    }

    #[DataProvider('validationDataProvider')]
    public function test_creating_a_contract_validates(
        string $key,
        mixed $value,
        ?string $expectedErrorMessage,
    ): void {
        $user = User::factory()->admin()->create();

        $response = $this->actingAs($user)->postJson(route('admin.contracts.store'), [
            $key => $value,
        ]);

        if ($expectedErrorMessage !== null && $expectedErrorMessage !== '' && $expectedErrorMessage !== '0') {
            $response->assertUnprocessable();
            $response->assertJsonValidationErrors([$key => $expectedErrorMessage]);
        } else {
            $response->assertJsonMissingValidationErrors($key);
        }
    }

    #[DataProvider('validationDataProvider')]
    public function test_updating_a_contract_validates(
        string $key,
        mixed $value,
        ?string $expectedErrorMessage,
    ): void {
        $user = User::factory()->admin()->create();
        $contract = DriverContract::factory()->create();

        $response = $this->actingAs($user)->putJson(route('admin.contracts.update', ['contract' => $contract->id]), [
            $key => $value,
        ]);

        if ($expectedErrorMessage !== null && $expectedErrorMessage !== '' && $expectedErrorMessage !== '0') {
            $response->assertUnprocessable();
            $response->assertJsonValidationErrors([$key => $expectedErrorMessage]);
        } else {
            $response->assertJsonMissingValidationErrors($key);
        }
    }

    public static function validationDataProvider(): Iterator
    {
        yield 'driver.required' => [
            'driver',
            '',
            'The driver field is required.'
        ];
        yield 'driver.integer' => [
            'driver',
            'not-an-integer',
            'The driver field must be an integer.'
        ];
        yield 'driver.exists' => [
            'driver',
            1,
            'The selected driver is invalid.',
        ];
        yield 'team.required' => [
            'team',
            '',
            'The team field is required.'
        ];
        yield 'team.integer' => [
            'team',
            'not-an-integer',
            'The team field must be an integer.'
        ];
        yield 'team.exists' => [
            'team',
            1,
            'The selected team is invalid.',
        ];
        yield 'start_date.required' => [
            'start_date',
            '',
            'The start date field is required.'
        ];
        yield 'start_date.date' => [
            'start_date',
            'not-a-date',
            'The start date field must be a valid date.'
        ];
        yield 'end_date.empty' => [
            'end_date',
            '',
            null
        ];
        yield 'end_date.date' => [
            'end_date',
            'not-a-date',
            'The end date field must be a valid date.'
        ];
    }

    public function test_creating_a_contract_validates_end_date_must_be_after_the_start_date(): void
    {
        $user = User::factory()->admin()->create();

        $response = $this->actingAs($user)->postJson(route('admin.contracts.store'), [
            'start_date' => '2021-01-01',
            'end_date' => '2021-01-01',
        ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['end_date' => 'The end date field must be a date after start date.']);
    }

    public function test_updating_a_contract_validates_end_date_must_be_after_the_start_date(): void
    {
        $user = User::factory()->admin()->create();
        $contract = DriverContract::factory()->create();

        $response = $this->actingAs($user)->putJson(route('admin.contracts.update', ['contract' => $contract->id]), [
            'start_date' => '2021-01-01',
            'end_date' => '2021-01-01',
        ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['end_date' => 'The end date field must be a date after start date.']);
    }

    #[DataProviderExternal(UniqueDriverContractTest::class, 'provider')]
    public function test_creating_a_contract_validates_a_contract_must_be_unique(
        ?string $existingStartDate,
        ?string $existingEndDate,
        string  $newStartDate,
        ?string $newEndDate,
        bool    $expectedResult,
    ): void {
        $user = User::factory()->admin()->create();
        $driver = Driver::factory()->create();
        $team = Team::factory()->create();
        if ($existingStartDate !== null) {
            DriverContract::factory()->create([
                'driver_id' => $driver,
                'team_id' => $team,
                'start_date' => $existingStartDate,
                'end_date' => $existingEndDate,
            ]);
        }

        $response = $this->actingAs($user)->postJson(route('admin.contracts.store'), [
            'driver' => $driver->id,
            'team' => $team->id,
            'start_date' => $newStartDate,
            'end_date' => $newEndDate,
        ]);

        if ($expectedResult) {
            $response->assertRedirectToRoute('admin.drivers.index');
            $this->assertDatabaseCount(DriverContract::class, $existingStartDate !== null ? 2 : 1);
        } else {
            $response->assertUnprocessable();
            $response->assertJsonValidationErrors(['driver' => 'The driver already has an active contract during the period.']);
        }
    }

    #[DataProviderExternal(UniqueDriverContractTest::class, 'provider')]
    public function test_updating_a_contract_validates_a_contract_must_be_unique(
        ?string $existingStartDate,
        ?string $existingEndDate,
        string  $newStartDate,
        ?string $newEndDate,
        bool    $expectedResult,
    ): void {
        $user = User::factory()->admin()->create();
        $driver = Driver::factory()->create();
        $team = Team::factory()->create();
        $contractToUpdate = DriverContract::factory()->create([
            'driver_id' => $driver,
            'team_id' => $team,
        ]);
        if ($existingStartDate !== null) {
            DriverContract::factory()->create([
                'driver_id' => $driver,
                'team_id' => $team,
                'start_date' => $existingStartDate,
                'end_date' => $existingEndDate,
            ]);
        }

        $response = $this->actingAs($user)->putJson(route('admin.contracts.update', ['contract' => $contractToUpdate->id]), [
            'driver' => $driver->id,
            'team' => $team->id,
            'start_date' => $newStartDate,
            'end_date' => $newEndDate,
        ]);

        if ($expectedResult) {
            $response->assertRedirectToRoute('admin.drivers.index');
            $this->assertDatabaseCount(DriverContract::class, $existingStartDate !== null ? 2 : 1);

            $updatedContract = $contractToUpdate->refresh();
            $this->assertSame($newStartDate, $updatedContract->start_date->format('Y-m-d'));
            $this->assertSame($newEndDate, $updatedContract->end_date?->format('Y-m-d'));
        } else {
            $response->assertUnprocessable();
            $response->assertJsonValidationErrors(['driver' => 'The driver already has an active contract during the period.']);
        }
    }

    public function test_an_admin_can_view_the_edit_contract_form(): void
    {
        $user = User::factory()->admin()->create();

        $teams = Team::factory()->count(3)->create();
        $drivers = Driver::factory()->count(3)->create();
        $contract = DriverContract::factory()->create([
            'driver_id' => $drivers[0],
            'team_id' => $teams[0],
        ]);

        $response = $this->actingAs($user)->get(route('admin.contracts.edit', ['contract' => $contract->id]));

        $response->assertOk();
        $response->assertInertia(function (AssertableInertia $assertableInertia) use ($contract): void {
            $assertableInertia->component('Admin/Contracts/Edit');
            $assertableInertia->where('contract', $contract->toArray());
            $assertableInertia->has('teams', 3);
            $assertableInertia->has('drivers', 3);
        });
    }

    public function test_an_admin_can_change_drivers_and_teams_of_a_contract(): void
    {$user = User::factory()->admin()->create();

        $teams = Team::factory()->count(2)->create();
        $drivers = Driver::factory()->count(2)->create();
        $contract = DriverContract::factory()->create([
            'driver_id' => $drivers->shift(),
            'team_id' => $teams->shift(),
        ]);

        $newDriver = $drivers->shift();
        $newTeam = $teams->shift();
        $response = $this->actingAs($user)->putJson(route('admin.contracts.update', ['contract' => $contract->id]), [
            'driver' => $newDriver->id,
            'team' => $newTeam->id,
            'start_date' => $contract->start_date,
            'end_date' => $contract->end_date,
        ]);

        $response->assertRedirectToRoute('admin.drivers.index');

        $updatedContract = $contract->refresh();
        $this->assertSame($newDriver->id, $updatedContract->driver_id);
        $this->assertSame($newTeam->id, $updatedContract->team_id);
        $this->assertEquals($contract->start_date, $updatedContract->start_date);
        $this->assertEquals($contract->end_date, $updatedContract->end_date);
    }
}
