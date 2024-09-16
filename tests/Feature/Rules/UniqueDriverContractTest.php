<?php

declare(strict_types=1);

namespace Tests\Feature\Rules;

use App\Models\Driver;
use App\Models\DriverContract;
use App\Models\Team;
use App\Rules\UniqueDriverContract;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

final class UniqueDriverContractTest extends TestCase
{
    use RefreshDatabase;

    #[DataProvider('provider')]
    public function test_unique_driver_contract_rule(
        ?string $existingStartDate,
        ?string $existingEndDate,
        string  $newStartDate,
        ?string $newEndDate,
        bool    $expectedResult,
    ): void
    {
        $driver = Driver::factory()->create();
        $team = Team::factory()->create();

        if ($existingStartDate !== null) {
            DriverContract::factory()->create([
                'driver_id' => $driver,
                'team_id' => $team,
                'start_date' => $existingStartDate,
                'end_date' => $existingEndDate
            ]);
        }

        $validator = validator([
            'driver' => $driver->id,
            'team' => $team->id,
            'start_date' => $newStartDate,
            'end_date' => $newEndDate,
        ], ['driver' => new UniqueDriverContract(null)]);

        $this->assertSame($expectedResult, $validator->passes());
    }

    #[DataProvider('provider')]
    public function test_unique_driver_contract_rule_with_except(
        ?string $existingStartDate,
        ?string $existingEndDate,
        string  $newStartDate,
        ?string $newEndDate,
        bool    $expectedResult,
    ): void
    {
        $driver = Driver::factory()->create();
        $team = Team::factory()->create();
        $except = DriverContract::factory()->create([
            'driver_id' => $driver,
            'team_id' => $team,
            'start_date' => '0000-01-01', // 0000 because it will include everything.
        ]);

        if ($existingStartDate !== null) {
            DriverContract::factory()->create([
                'driver_id' => $driver,
                'team_id' => $team,
                'start_date' => $existingStartDate,
                'end_date' => $existingEndDate
            ]);
        }

        $validator = validator([
            'driver' => $driver->id,
            'team' => $team->id,
            'start_date' => $newStartDate,
            'end_date' => $newEndDate,
        ], ['driver' => new UniqueDriverContract($except)]);

        $this->assertSame($expectedResult, $validator->passes());
    }

    public static function provider(): Iterator
    {
        yield 'No existing contracts should pass' => [
            null,
            null,
            '2000-01-01',
            null,
            true,
        ];
        yield 'No existing contracts with end date should pass' => [
            null,
            null,
            '2000-01-01',
            '2010-01-01',
            true,
        ];
        yield 'Existing contract ended before new contract starts should pass' => [
            '2000-01-01',
            '2010-01-01',
            '2020-01-01',
            null,
            true,
        ];
        yield 'Existing contract ended before new contract with enddate starts should pass' => [
            '2000-01-01',
            '2010-01-01',
            '2020-01-01',
            '2022-01-01',
            true,
        ];
        yield 'Existing contract starts after new contract should pass' => [
            '2020-01-01',
            '2022-01-01',
            '2000-01-01',
            '2010-01-01',
            true,
        ];
        yield 'Existing contract ends after start date of new contract should fail' => [
            '2000-01-01',
            '2010-01-01',
            '2005-01-01',
            null,
            false,
        ];
        yield 'Existing contract ends after start date of new contract with enddate should fail' => [
            '2000-01-01',
            '2010-01-01',
            '2005-01-01',
            '2015-01-01',
            false,
        ];
        yield 'new contract without enddate starts before existing contract should fail' => [
            '2005-01-01',
            '2010-01-01',
            '2000-01-01',
            null,
            false,
        ];
        yield 'new contract starts before existing contract and ends after should fail' => [
            '2005-01-01',
            '2010-01-01',
            '2000-01-01',
            '2015-01-01',
            false,
        ];
        yield 'new contract starts before existing contract and ends during existing contract should fail' => [
            '2005-01-01',
            '2010-01-01',
            '2000-01-01',
            '2008-01-01',
            false,
        ];
        yield 'new contract starts during existing contract and ends during existing contract should fail' => [
            '2000-01-01',
            '2020-01-01',
            '2005-01-01',
            '2015-01-01',
            false,
        ];
        yield 'new contract starts during existing contract and ends after existing contract should fail' => [
            '2000-01-01',
            '2020-01-01',
            '2005-01-01',
            '2025-01-01',
            false,
        ];
        yield 'new contract starts during existing contract and end date is null should fail' => [
            '2000-01-01',
            '2020-01-01',
            '2005-01-01',
            null,
            false,
        ];
        yield 'new contract starts as the existing one ends should fail' => [
            '2021-01-01',
            '2022-01-01',
            '2022-01-01',
            null,
            false,
        ];
        yield 'new contract starts while existing contract does not have an end date' => [
            '2021-01-01',
            null,
            '2022-01-01',
            null,
            false,
        ];
        yield 'new contract end date is after existing contract start date' => [
            '2021-01-01',
            null,
            '2020-01-01',
            '2022-01-01',
            false,
        ];
    }
}
