<?php

declare(strict_types=1);
namespace Rodrom\Advent202216\Test;

use PHPUnit\Framework\TestCase;
use Rodrom\Advent202216\Game;

class GameTest extends TestCase
{
    public function test_read_input(): void
    {
        $input = file_get_contents(__DIR__ . '/../example.txt');
        $game = Game::readInput($input);
        $this->assertInstanceOf(Game::class, $game);
    }

    public function test_start_game(): void
    {
        $input = file_get_contents(__DIR__ . '/../example.txt');
        $game = Game::readInput($input);
        $max = $game->start("AA", 30);

        $this->assertEquals(1651, $max);
    }
}
