<?php

declare(strict_types=1);

namespace Tests\Feature\Jobs\User;

use App\Jobs\User\PromoteToAdminJob;
use App\Models\User;
use App\Notifications\User\PromotedToAdmin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

final class PromoteToAdminJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_be_promoted_to_admin(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        Notification::fake();

        Bus::dispatchSync(new PromoteToAdminJob($user));

        $this->assertTrue($user->refresh()->is_admin);

        Notification::assertSentTo([$user], PromotedToAdmin::class);
    }

    public function test_promoting_an_admin_doesnt_notify_it(): void
    {
        $user = User::factory()->admin()->create();

        Notification::fake();

        Bus::dispatchSync(new PromoteToAdminJob($user));

        Notification::assertNothingSent();
    }
}
