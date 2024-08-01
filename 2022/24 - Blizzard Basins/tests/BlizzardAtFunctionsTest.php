<?php

declare(strict_types=1);
namespace Rodrom\Advent202224\Test;

use PHPUnit\Framework\TestCase;
use Rodrom\Advent202224\Board;

class BlizzardAtFunctionsTest extends TestCase
{
    protected Board $board;

    protected function setUp (): void
    {
        $this->board = Board::readInput(file_get_contents(__DIR__ . "/../example.txt"));
    }

    public function test_blizzard_at_north_t0(): void
    {
        $board = $this->board;
        $this->assertFalse($board->blizzardNorthAt(0,0,0));
        $this->assertTrue($board->blizzardNorthAt(4,0,0));
        $this->assertTrue($board->blizzardNorthAt(1,3,0));
        $this->assertTrue($board->blizzardNorthAt(3,3,0));
        $this->assertTrue($board->blizzardNorthAt(4,3,0));
    }

    public function test_blizzard_at_north_t1(): void
    {
        $board = $this->board;
        $this->assertFalse($board->blizzardNorthAt(0,0,1));
        $this->assertTrue($board->blizzardNorthAt(4,3,1));
        $this->assertFalse($board->blizzardNorthAt(4,0,1));
        $this->assertTrue($board->blizzardNorthAt(1,2,1));
        $this->assertTrue($board->blizzardNorthAt(3,2,1));
        $this->assertTrue($board->blizzardNorthAt(4,2,1));
    }

    public function test_blizzard_at_north_t234(): void
    {
        $board = $this->board;
        $this->assertFalse($board->blizzardNorthAt(0,0,1));
        $this->assertTrue($board->blizzardNorthAt(4,2,2));
        $this->assertTrue($board->blizzardNorthAt(4,1,3));
        $this->assertTrue($board->blizzardNorthAt(4,0,4));

        $this->assertTrue($board->blizzardNorthAt(3,1,2));
        $this->assertTrue($board->blizzardNorthAt(3,0,3));
        $this->assertTrue($board->blizzardNorthAt(3,3,4));
    }

    public function test_blizzard_at_south_t0123(): void
    {
        $board = $this->board;
    
        $this->assertFalse($board->blizzardSouthAt(0,0,0));
        $this->assertFalse($board->blizzardSouthAt(0,0,1));
        $this->assertFalse($board->blizzardSouthAt(0,0,2));
        $this->assertFalse($board->blizzardSouthAt(0,0,3));

        $this->assertTrue($board->blizzardSouthAt(1,2,0));
        $this->assertFalse($board->blizzardSouthAt(1,2,1));
        $this->assertTrue($board->blizzardSouthAt(1,3,1));
        $this->assertTrue($board->blizzardSouthAt(1,0,2));
        $this->assertTrue($board->blizzardSouthAt(1,1,3));
        $this->assertTrue($board->blizzardSouthAt(1,2,4));

    }

    public function test_blizzard_at_west_t0123(): void
    {
        $board = $this->board;
    
        $this->assertFalse($board->blizzardWestAt(0,0,0));
        $this->assertFalse($board->blizzardWestAt(0,0,1));
        $this->assertFalse($board->blizzardWestAt(0,0,2));

        $this->assertTrue($board->blizzardWestAt(3,0,0));
        $this->assertFalse($board->blizzardWestAt(3,0,1));
        $this->assertTrue($board->blizzardWestAt(2,0,1));
        $this->assertTrue($board->blizzardWestAt(1,0,2));
        $this->assertTrue($board->blizzardWestAt(0,0,3));
        $this->assertTrue($board->blizzardWestAt(3,0,0));

    }

    public function test_blizzard_at_east_t0123(): void
    {
        $board = $this->board;
    
        $this->assertTrue($board->blizzardEastAt(0,0,0));
        $this->assertFalse($board->blizzardEastAt(0,0,1));
        $this->assertFalse($board->blizzardEastAt(0,0,2));

        $this->assertTrue($board->blizzardEastAt(5,3,0));
        $this->assertTrue($board->blizzardEastAt(0,3,1));
        $this->assertTrue($board->blizzardEastAt(1,3,2));
        $this->assertTrue($board->blizzardEastAt(2,3,3));
        $this->assertTrue($board->blizzardEastAt(3,3,4));
        $this->assertTrue($board->blizzardEastAt(4,3,5));

    }

    public function test_blizzard_at_t0123(): void
    {
        $board = $this->board;
        $this->assertTrue($board->blizzardAt(5,3,0));
        $this->assertTrue($board->blizzardAt(5,3,1));
        $this->assertFalse($board->blizzardAt(5,3,2));

        $this->assertFalse($board->blizzardAt(2,0,0));
        $this->assertTrue($board->blizzardAt(2,0,1));
    }
}