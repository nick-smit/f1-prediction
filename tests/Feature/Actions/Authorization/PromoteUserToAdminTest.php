<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Authorization;

use App\Actions\Authorization\PromoteUserToAdmin;
use App\Models\User;
use App\Notifications\User\PromotedToAdmin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\TestCase;

#[CoversClass(PromoteUserToAdmin::class)]
final class PromoteUserToAdminTest extends TestCase
{
    use RefreshDatabase;
    public function test_it_does_nothing_if_the_user_is_already_an_admin(): void
    {
        $user = User::factory()->admin()->create();

        $this->queryCounted(fn () => $this->app->make(PromoteUserToAdmin::class)->handle($user));

        $this->assertQueryCount(0);
    }

    public function it_sets_the_is_admin_attribute_to_true_and_saves_the_user(): void
    {
        $user = User::factory()->create();

        $this->app->make(PromoteUserToAdmin::class)->handle($user);

        $this->assertTrue($user->fresh()->is_admin);
    }

    public function it_notifies_the_user(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $this->app->make(PromoteUserToAdmin::class)->handle($user);

        Notification::assertSentTo([$user], PromotedToAdmin::class);
    }
}
