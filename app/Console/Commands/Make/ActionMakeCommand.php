<?php

declare(strict_types=1);

namespace App\Console\Commands\Make;

use Illuminate\Console\Concerns\CreatesMatchingTest;
use Illuminate\Console\GeneratorCommand;
use Override;

class ActionMakeCommand extends GeneratorCommand
{
    use CreatesMatchingTest;

    /**
     * The type of class being generated
     *
     * @var string
     */
    protected $type = 'Action';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:action {name : The name of the action}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Action class';

    #[Override]
    protected function getStub(): string
    {
        $relativePath = '/stubs/action.stub';

        return file_exists($customPath = $this->laravel->basePath(trim($relativePath, '/')))
            ? $customPath
            : __DIR__ . $relativePath;
    }

    #[Override]
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '\Actions';
    }
}
