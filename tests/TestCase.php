<?php

declare(strict_types=1);

namespace Tests;

use Closure;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Override;
use RuntimeException;

abstract class TestCase extends BaseTestCase
{
    private bool $queryCountEnabled = false;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();

        $this->queryCountEnabled = false;
    }

    protected function queryCounted(Closure $closure): mixed
    {
        $this->queryCountEnabled = true;

        DB::enableQueryLog();
        $result = $closure();
        DB::disableQueryLog();

        return $result;
    }

    protected function assertQueryCount(int $value): void
    {
        if (!$this->queryCountEnabled) {
            throw new RuntimeException('Cannot assert query count. Please use TestCase::queryCounted() to count queries.');
        }

        $this->assertCount($value, DB::getQueryLog());
    }

    protected function assertFileContains(array $array, string $file): void
    {
        $this->assertFileExists($file);

        $contents = File::get($file);

        foreach ($array as $expected) {
            $this->assertStringContainsString($expected, $contents);
        }
    }

}
