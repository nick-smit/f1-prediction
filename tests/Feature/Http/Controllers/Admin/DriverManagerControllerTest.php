<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Admin;

use App\Models\Driver;
use App\Models\DriverContract;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

final class DriverManagerControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_viewing_index(): void
    {
        $user = User::factory()->admin()->create();
        DriverContract::factory()->count(5)->create();

        $response = $this->actingAs($user)->get(route('admin.drivers.index'));

        $response->assertOk();
        $response->assertInertia(function (AssertableInertia $assertableInertia): void {
            $assertableInertia->component('Admin/Drivers/Index');
            $assertableInertia->has('drivers.data', 5);
        });
    }

    public function test_viewing_the_index_paginates_drivers(): void
    {
        $user = User::factory()->admin()->create();
        DriverContract::factory()->count(20)->create();

        $response = $this->actingAs($user)->get(route('admin.drivers.index'));

        $response->assertOk();
        $response->assertInertia(function (AssertableInertia $assertableInertia): void {
            $assertableInertia->component('Admin/Drivers/Index');
            $assertableInertia->has('drivers.data', 15);
        });
    }

    public function test_viewing_the_index_paginates_driver_and_retrieves_page_2(): void
    {
        $user = User::factory()->admin()->create();
        DriverContract::factory()->count(20)->create();

        $response = $this->actingAs($user)->get(route('admin.drivers.index', ['page' => 2]));

        $response->assertOk();
        $response->assertInertia(function (AssertableInertia $assertableInertia): void {
            $assertableInertia->component('Admin/Drivers/Index');
            $assertableInertia->has('drivers.data', 5);
        });
    }

    public function test_viewing_the_index_paginates_drivers_and_filters_inactive_drivers_by_default(): void
    {
        $user = User::factory()->admin()->create();
        DriverContract::factory()->count(5)->create();
        Driver::factory()->count(5)->create();

        $response = $this->actingAs($user)->get(route('admin.drivers.index'));

        $response->assertOk();
        $response->assertInertia(function (AssertableInertia $assertableInertia): void {
            $assertableInertia->component('Admin/Drivers/Index');
            $assertableInertia->has('drivers.data', 5);
        });
    }

    public function test_viewing_the_index_paginates_drivers_and_reacts_to_the_hide_inactive_filter(): void
    {
        $user = User::factory()->admin()->create();
        DriverContract::factory()->count(5)->create();
        Driver::factory()->count(5)->create();

        $response = $this->actingAs($user)->get(route('admin.drivers.index', ['hide_inactive' => '0']));

        $response->assertOk();
        $response->assertInertia(function (AssertableInertia $assertableInertia): void {
            $assertableInertia->component('Admin/Drivers/Index');
            $assertableInertia->has('drivers.data', 10);
        });
    }

    public function test_viewing_the_index_paginates_drivers_and_reacts_to_the_search_filter(): void
    {
        $user = User::factory()->admin()->create();
        $driver = Driver::factory()->create([
            'name' => 'John Doe'
        ]);
        DriverContract::factory()->create(['driver_id' => $driver]);
        DriverContract::factory()->count(5)->create();

        $response = $this->actingAs($user)->get(route('admin.drivers.index', ['s' => 'John Doe']));

        $response->assertOk();
        $response->assertInertia(function (AssertableInertia $assertableInertia): void {
            $assertableInertia->component('Admin/Drivers/Index');
            $assertableInertia->has('drivers.data', 1);
        });
    }
}
