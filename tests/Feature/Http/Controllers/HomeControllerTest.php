<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

final class HomeControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $testResponse = $this->get(route('home'));

        $testResponse->assertStatus(200);
        $testResponse->assertInertia(static function (AssertableInertia $assertableInertia): void {
            $assertableInertia->component('Home');
        });
    }
}
