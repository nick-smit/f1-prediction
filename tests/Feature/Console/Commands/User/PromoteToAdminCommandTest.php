<?php

declare(strict_types=1);

namespace Tests\Feature\Console\Commands\User;

use App\Models\User;
use App\Notifications\User\PromotedToAdmin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

final class PromoteToAdminCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_running_the_command_without_a_user_id_will_ask_you_to_specify_a_user_and_filters_by_name(): void
    {
        $users = User::factory()->count(2)->sequence(
            ['name' => 'nick', 'email' => 'user1@example.com'],
            ['name' => 'Piet', 'email' => 'user2@example.com'],
        )->create();

        $user = $users[0];
        $this->artisan('user:promote-to-admin')
            ->expectsSearch(
                'Please select a user',
                $user->id,
                'ni',
                [$user->id => 'nick <user1@example.com>']
            )
            ->expectsConfirmation('Are you sure you want to make nick <user1@example.com> an administrator?', 'yes')
            ->expectsOutput('User is successfully promoted to an administrator!')
            ->assertOk();
    }

    public function test_running_the_command_without_a_user_id_will_ask_you_to_specify_a_user_and_filters_by_email(): void
    {
        Notification::fake();

        $users = User::factory()->count(2)->sequence(
            ['name' => 'nick', 'email' => 'user1@example.com'],
            ['name' => 'Piet', 'email' => 'user2@example.com'],
        )->create();

        $user = $users[1];
        $this->artisan('user:promote-to-admin')
            ->expectsSearch(
                'Please select a user',
                $user->id,
                'user2',
                [$user->id => 'Piet <user2@example.com>']
            )
            ->expectsConfirmation('Are you sure you want to make Piet <user2@example.com> an administrator?', 'yes')
            ->expectsOutput('User is successfully promoted to an administrator!')
            ->assertOk();

        $this->assertTrue($user->refresh()->is_admin);
        Notification::assertSentTo([$user], PromotedToAdmin::class);
    }

    public function test_running_the_command_with_a_user_id(): void
    {
        Notification::fake();
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        $this->artisan('user:promote-to-admin', ['user-id' => $user->id])
            ->expectsConfirmation('Are you sure you want to make John Doe <john@example.com> an administrator?', 'yes')
            ->expectsOutput('User is successfully promoted to an administrator!')
            ->assertOk();

        $this->assertTrue($user->refresh()->is_admin);
        Notification::assertSentTo([$user], PromotedToAdmin::class);
    }

    public function test_canceling_the_confirmation_cancels_the_action(): void
    {
        Notification::fake();
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        $this->artisan('user:promote-to-admin', ['user-id' => $user->id])
            ->expectsConfirmation('Are you sure you want to make John Doe <john@example.com> an administrator?', 'no')
            ->expectsOutput('Action cancelled due to user input')
            ->assertFailed();

        $this->assertFalse($user->refresh()->is_admin);
        Notification::assertNothingSent();
    }

    public function test_running_the_command_with_an_invalid_user_id_will_crash(): void
    {
        $this->artisan('user:promote-to-admin 0')
            ->expectsOutput('The specified user was not found.')
            ->assertFailed();
    }
}
