<?php

declare(strict_types=1);
namespace Rodrom\Advent202217\Test;

use PHPUnit\Framework\TestCase;
use Rodrom\Advent202217\Board;
use Rodrom\Advent202217\Piece;

class Part2Test extends TestCase
{
    public function test_check_trim_function(): void
    {
        $board = new Board(2, 0, 0, 0);
        $piece = new Piece(
            height: 2,
            width: 2,
            form: [
                0 => [ -1 => '#', 0 => '#' ],
                1 => [ -1 => '#', 0 => '#' ],
            ],
            id: 0
        );

        $board->setJetsSequence('<<<');
        $board->setPiecesSequence([$piece]);
        $board->playPieceAtTheTop();
        $result = $board->trim(1);

        $this->assertEquals(2, $result);
    }
}