<?php

declare(strict_types=1);

namespace Tests\Feature\Notifications\User;

use App\Notifications\User\PromotedToAdmin;
use Tests\TestCase;

final class PromotedToAdminTest extends TestCase
{
    public function test_promoted_to_admin_can_be_rendered_as_a_mail(): void
    {
        $notification = new PromotedToAdmin();

        $mail = $notification->toMail();
        $htmlString = $mail->render();
        $this->assertTrue($htmlString->isNotEmpty());
    }
}
