<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Admin;

use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

final class TeamManagerControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_viewing_index(): void
    {
        $user = User::factory()->admin()->create();
        Team::factory()->count(5)->create();

        $response = $this->actingAs($user)->get(route('admin.teams.index'));

        $response->assertOk();
        $response->assertInertia(function (AssertableInertia $assertableInertia): void {
            $assertableInertia->component('Admin/Teams/Index');
            $assertableInertia->has('teams.data', 5);
        });
    }

    public function test_viewing_the_index_paginates_drivers(): void
    {
        $user = User::factory()->admin()->create();
        Team::factory()->count(20)->create();

        $response = $this->actingAs($user)->get(route('admin.teams.index'));

        $response->assertOk();
        $response->assertInertia(function (AssertableInertia $assertableInertia): void {
            $assertableInertia->component('Admin/Teams/Index');
            $assertableInertia->has('teams.data', 15);
        });
    }

    public function test_viewing_the_index_paginates_driver_and_retrieves_page_2(): void
    {
        $user = User::factory()->admin()->create();
        Team::factory()->count(20)->create();

        $response = $this->actingAs($user)->get(route('admin.teams.index', ['page' => 2]));

        $response->assertOk();
        $response->assertInertia(function (AssertableInertia $assertableInertia): void {
            $assertableInertia->component('Admin/Teams/Index');
            $assertableInertia->has('teams.data', 5);
        });
    }

    public function test_viewing_the_index_paginates_drivers_and_reacts_to_the_search_filter(): void
    {
        $user = User::factory()->admin()->create();
        Team::factory()->create([
            'name' => 'John Doe Racing'
        ]);

        $response = $this->actingAs($user)->get(route('admin.teams.index', ['s' => 'John Doe']));

        $response->assertOk();
        $response->assertInertia(function (AssertableInertia $assertableInertia): void {
            $assertableInertia->component('Admin/Teams/Index');
            $assertableInertia->has('teams.data', 1);
        });
    }

    public function test_the_driver_edit_form_can_be_shown(): void
    {
        $user = User::factory()->admin()->create();
        $team = Team::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.teams.edit', ['team' => $team->id]));

        $response->assertOk();
        $response->assertInertia(function (AssertableInertia $assertableInertia) use ($team): void {
            $assertableInertia->component('Admin/Teams/Edit');
            $assertableInertia->where('team', $team->toArray());
        });
    }

    public function test_an_admin_can_update_a_driver(): void
    {
        $user = User::factory()->admin()->create();
        $team = Team::factory()->create(['name' => 'old name']);

        $response = $this->actingAs($user)->put(route('admin.teams.update', ['team' => $team->id]), [
            'name' => 'new name',
        ]);

        $response->assertRedirectToRoute('admin.teams.index');

        $team = $team->refresh();
        $this->assertSame('new name', $team->name);
    }

    public function test_the_name_is_required_when_updating_a_driver(): void
    {
        $user = User::factory()->admin()->create();
        $team = Team::factory()->create();

        $response = $this->actingAs($user)->putJson(route('admin.teams.update', ['team' => $team->id]), [
            'name' => '',
        ]);

        $response->assertJsonValidationErrors(['name' => 'The name field is required.']);
    }


    public function test_the_name_must_be_a_string_when_updating_a_driver(): void
    {
        $user = User::factory()->admin()->create();
        $team = Team::factory()->create();

        $response = $this->actingAs($user)->putJson(route('admin.teams.update', ['team' => $team->id]), [
            'name' => 123,
        ]);

        $response->assertJsonValidationErrors(['name' => 'The name field must be a string.']);
    }

    public function test_the_driver_name_must_be_unique_when_updating_a_driver(): void
    {
        $user = User::factory()->admin()->create();
        Team::factory()->create(['name' => 'John Doe']);
        $team = Team::factory()->create();

        $response = $this->actingAs($user)->putJson(route('admin.teams.update', ['team' => $team->id]), [
            'name' => 'John Doe',
        ]);

        $response->assertJsonValidationErrors(['name' => 'The name has already been taken.']);
    }

    public function test_the_driver_create_form_can_be_shown(): void
    {
        $user = User::factory()->admin()->create();

        $response = $this->actingAs($user)->get(route('admin.teams.create'));

        $response->assertOk();
        $response->assertInertia(function (AssertableInertia $assertableInertia): void {
            $assertableInertia->component('Admin/Teams/Create');
        });
    }

    public function test_an_admin_can_create_a_driver(): void
    {
        $user = User::factory()->admin()->create();

        $response = $this->actingAs($user)->post(route('admin.teams.store'), [
            'name' => 'John Doe',
        ]);

        $response->assertRedirectToRoute('admin.teams.index');
        $this->assertDatabaseCount(Team::class, 1);
        $team = Team::query()->first();
        $this->assertSame('John Doe', $team->name);
    }

    public function test_the_name_is_required_when_creating_a_driver(): void
    {
        $user = User::factory()->admin()->create();

        $response = $this->actingAs($user)->postJson(route('admin.teams.store'), [
            'name' => '',
        ]);

        $response->assertJsonValidationErrors(['name' => 'The name field is required.']);
    }

    public function test_the_name_must_be_a_string_when_creating_a_driver(): void
    {
        $user = User::factory()->admin()->create();

        $response = $this->actingAs($user)->postJson(route('admin.teams.store'), [
            'name' => 123,
        ]);

        $response->assertJsonValidationErrors(['name' => 'The name field must be a string.']);
    }

    public function test_the_name_must_be_unique_when_creating_a_driver(): void
    {
        $user = User::factory()->admin()->create();
        Team::factory()->create(['name' => 'John Doe']);

        $response = $this->actingAs($user)->postJson(route('admin.teams.store'), [
            'name' => 'John Doe',
        ]);

        $response->assertJsonValidationErrors(['name' => 'The name has already been taken.']);
    }
}
