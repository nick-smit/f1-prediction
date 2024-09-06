<?php

declare(strict_types=1);

namespace Tests\Unit\GrandPrixGuessr\DTO;

use App\GrandPrixGuessr\DTO\Team;
use Assert\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class TeamTest extends TestCase
{
    public function test_a_team_can_be_created(): void
    {
        $team = new Team(1, 'Red Bull Racing');

        $this->assertSame(1, $team->id);
        $this->assertSame('Red Bull Racing', $team->name);
    }

    public function test_a_team_id_cannot_be_below_zero(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Team id can not be less than 0');

        new Team(-1, 'Red Bull Racing');
    }

    public function test_a_team_name_cannot_be_blank(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Team name can not be empty');

        new Team(0, '');
    }
}
