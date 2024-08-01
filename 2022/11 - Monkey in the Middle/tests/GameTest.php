<?php

declare(strict_types=1);
namespace Rodrom\Advent202211\Test;
use PHPUnit\Framework\TestCase;
use Rodrom\Advent202211\Game;

class GameTest extends TestCase
{
    public function testLoadMonkeysFromFile(): void
    {
        $game = new Game();
        $game->loadMonkeysFromFile(__DIR__ . "\\..\\example.txt");
        $this->assertEquals(4,count($game->getMonkeys()));
    }

    public function testGame1Round(): void
    {
        $game = new Game(max_rounds: 1);
        $game->loadMonkeysFromFile(__DIR__ . "\\..\\example.txt");

        $expected = 6 * 4;
        
        $this->assertEquals($expected, $game->play());
    }
}