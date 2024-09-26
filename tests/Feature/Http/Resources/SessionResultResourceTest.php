<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Resources;

use App\Http\Resources\SessionResultResource;
use App\Models\SessionResult;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\TestCase;

#[CoversClass(SessionResultResource::class)]
final class SessionResultResourceTest extends TestCase
{
    use RefreshDatabase;
    public function test_a_session_result_can_be_transformed(): void
    {
        $sessionResult = SessionResult::factory()->create();

        $resource = new SessionResultResource($sessionResult);

        $response = $resource->response();

        $array = json_decode($response->getContent(), true)['data'];

        $this->assertSame($sessionResult->p1->jsonSerialize(), $array['p1']);
        $this->assertSame($sessionResult->p2->jsonSerialize(), $array['p2']);
        $this->assertSame($sessionResult->p3->jsonSerialize(), $array['p3']);
        $this->assertSame($sessionResult->p4->jsonSerialize(), $array['p4']);
        $this->assertSame($sessionResult->p5->jsonSerialize(), $array['p5']);
        $this->assertSame($sessionResult->p6->jsonSerialize(), $array['p6']);
        $this->assertSame($sessionResult->p7->jsonSerialize(), $array['p7']);
        $this->assertSame($sessionResult->p8->jsonSerialize(), $array['p8']);
        $this->assertSame($sessionResult->p9->jsonSerialize(), $array['p9']);
        $this->assertSame($sessionResult->p10->jsonSerialize(), $array['p10']);
    }
}
