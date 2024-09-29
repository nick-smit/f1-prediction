<?php

declare(strict_types=1);

namespace Tests\Feature\Console\Commands\Make;

use App\Console\Commands\Make\ActionMakeCommand;
use Illuminate\Support\Facades\File;
use Override;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\TestCase;

#[CoversClass(ActionMakeCommand::class)]
final class ActionMakeCommandTest extends TestCase
{
    #[Override]
    protected function tearDown(): void
    {
        parent::tearDown();

        File::delete(base_path('app/Actions/FooAction.php'));
        if (File::isEmptyDirectory(base_path('app/Actions'))) {
            File::deleteDirectory(base_path('app/Actions'));
        }
    }

    public function test_it_can_generate_the_action_file(): void
    {
        $this->artisan('make:action', ['name' => 'FooAction'])
            ->assertExitCode(0);

        $this->assertFileContains([
            'namespace App\Actions;',
            'class FooAction',
            'public function handle(): void',
        ], base_path('app/Actions/FooAction.php'));
    }
}
