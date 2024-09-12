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

    public function test_the_driver_edit_form_can_be_shown(): void
    {
        $user = User::factory()->admin()->create();
        $driver = Driver::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.drivers.edit', ['driver' => $driver->id]));

        $response->assertOk();
        $response->assertInertia(function (AssertableInertia $assertableInertia) use ($driver): void {
            $assertableInertia->component('Admin/Drivers/Edit');
            $assertableInertia->where('driver', $driver->toArray());
        });
    }

    public function test_an_admin_can_update_a_driver(): void
    {
        $user = User::factory()->admin()->create();
        $driver = Driver::factory()->create(['name' => 'old name', 'number' => 1]);

        $response = $this->actingAs($user)->put(route('admin.drivers.update', ['driver' => $driver->id]), [
            'name' => 'new name',
            'number' => 99
        ]);

        $response->assertRedirectToRoute('admin.drivers.index');

        $driver = $driver->refresh();
        $this->assertSame('new name', $driver->name);
        $this->assertSame(99, $driver->number);
    }

    public function test_the_driver_number_is_required_when_updating_a_driver(): void
    {
        $user = User::factory()->admin()->create();
        $driver = Driver::factory()->create();

        $response = $this->actingAs($user)->putJson(route('admin.drivers.update', ['driver' => $driver->id]), [
            'number' => '',
        ]);

        $response->assertJsonValidationErrors(['number' => 'The number field is required.']);
    }

    public function test_the_driver_number_must_be_a_number_when_updating_a_driver(): void
    {
        $user = User::factory()->admin()->create();
        $driver = Driver::factory()->create();

        $response = $this->actingAs($user)->putJson(route('admin.drivers.update', ['driver' => $driver->id]), [
            'number' => 'not a number',
        ]);

        $response->assertJsonValidationErrors(['number' => 'The number field must be an integer.']);
    }

    public function test_the_driver_number_must_be_above_0_when_updating_a_driver(): void
    {
        $user = User::factory()->admin()->create();
        $driver = Driver::factory()->create();

        $response = $this->actingAs($user)->putJson(route('admin.drivers.update', ['driver' => $driver->id]), [
            'number' => 0,
        ]);

        $response->assertJsonValidationErrors(['number' => 'The number field must be between 1 and 99.']);
    }

    public function test_the_driver_number_must_be_below_100_when_updating_a_driver(): void
    {
        $user = User::factory()->admin()->create();
        $driver = Driver::factory()->create();

        $response = $this->actingAs($user)->putJson(route('admin.drivers.update', ['driver' => $driver->id]), [
            'number' => 100,
        ]);

        $response->assertJsonValidationErrors(['number' => 'The number field must be between 1 and 99.']);
    }

    public function test_the_driver_name_is_required_when_updating_a_driver(): void
    {
        $user = User::factory()->admin()->create();
        $driver = Driver::factory()->create();

        $response = $this->actingAs($user)->putJson(route('admin.drivers.update', ['driver' => $driver->id]), [
            'name' => '',
        ]);

        $response->assertJsonValidationErrors(['name' => 'The name field is required.']);
    }

    public function test_the_driver_name_must_be_a_string_when_updating_a_driver(): void
    {
        $user = User::factory()->admin()->create();
        $driver = Driver::factory()->create();

        $response = $this->actingAs($user)->putJson(route('admin.drivers.update', ['driver' => $driver->id]), [
            'name' => 123,
        ]);

        $response->assertJsonValidationErrors(['name' => 'The name field must be a string.']);
    }

    public function test_the_driver_name_must_be_unique_when_updating_a_driver(): void
    {
        $user = User::factory()->admin()->create();
        Driver::factory()->create(['name' => 'John Doe']);
        $driver = Driver::factory()->create();

        $response = $this->actingAs($user)->putJson(route('admin.drivers.update', ['driver' => $driver->id]), [
            'name' => 'John Doe',
        ]);

        $response->assertJsonValidationErrors(['name' => 'The name has already been taken.']);
    }

    public function test_the_driver_create_form_can_be_shown(): void
    {
        $user = User::factory()->admin()->create();

        $response = $this->actingAs($user)->get(route('admin.drivers.create'));

        $response->assertOk();
        $response->assertInertia(function (AssertableInertia $assertableInertia): void {
            $assertableInertia->component('Admin/Drivers/Create');
        });
    }

    public function test_an_admin_can_create_a_driver(): void
    {
        $user = User::factory()->admin()->create();

        $response = $this->actingAs($user)->post(route('admin.drivers.store'), [
            'name' => 'John Doe',
            'number' => 1
        ]);

        $response->assertRedirectToRoute('admin.drivers.index');
        $this->assertDatabaseCount(Driver::class, 1);
        $driver = Driver::query()->first();
        $this->assertSame('John Doe', $driver->name);
        $this->assertSame(1, $driver->number);
    }

    public function test_the_driver_number_is_required_when_creating_a_driver(): void
    {
        $user = User::factory()->admin()->create();

        $response = $this->actingAs($user)->postJson(route('admin.drivers.store'), [
            'number' => '',
        ]);

        $response->assertJsonValidationErrors(['number' => 'The number field is required.']);
    }

    public function test_the_driver_number_must_be_a_number_when_creating_a_driver(): void
    {
        $user = User::factory()->admin()->create();

        $response = $this->actingAs($user)->postJson(route('admin.drivers.store'), [
            'number' => 'not a number',
        ]);

        $response->assertJsonValidationErrors(['number' => 'The number field must be an integer.']);
    }

    public function test_the_driver_number_must_be_above_0_when_creating_a_driver(): void
    {
        $user = User::factory()->admin()->create();

        $response = $this->actingAs($user)->postJson(route('admin.drivers.store'), [
            'number' => 0,
        ]);

        $response->assertJsonValidationErrors(['number' => 'The number field must be between 1 and 99.']);
    }

    public function test_the_driver_number_must_be_below_100_when_creating_a_driver(): void
    {
        $user = User::factory()->admin()->create();

        $response = $this->actingAs($user)->postJson(route('admin.drivers.store'), [
            'number' => 100,
        ]);

        $response->assertJsonValidationErrors(['number' => 'The number field must be between 1 and 99.']);
    }

    public function test_the_driver_name_is_required_when_creating_a_driver(): void
    {
        $user = User::factory()->admin()->create();

        $response = $this->actingAs($user)->postJson(route('admin.drivers.store'), [
            'name' => '',
        ]);

        $response->assertJsonValidationErrors(['name' => 'The name field is required.']);
    }

    public function test_the_driver_name_must_be_a_string_when_creating_a_driver(): void
    {
        $user = User::factory()->admin()->create();

        $response = $this->actingAs($user)->postJson(route('admin.drivers.store'), [
            'name' => 123,
        ]);

        $response->assertJsonValidationErrors(['name' => 'The name field must be a string.']);
    }

    public function test_the_driver_name_must_be_unique_when_creating_a_driver(): void
    {
        $user = User::factory()->admin()->create();
        Driver::factory()->create(['name' => 'John Doe']);

        $response = $this->actingAs($user)->postJson(route('admin.drivers.store'), [
            'name' => 'John Doe',
        ]);

        $response->assertJsonValidationErrors(['name' => 'The name has already been taken.']);
    }
}
