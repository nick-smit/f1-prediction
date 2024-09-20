<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Resources;

use App\Http\Resources\ActionResultResource;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ActionResultResource::class)]
final class ActionResultResourceTest extends TestCase
{
    public function test_action_result_resource(): void
    {
        $resource = new ActionResultResource(true, 'some message');

        $this->assertSame('{"success":true,"message":"some message"}', json_encode($resource, JSON_THROW_ON_ERROR));
    }
}
