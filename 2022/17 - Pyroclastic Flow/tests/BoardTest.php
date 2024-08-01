<?php

declare(strict_types=1);
namespace Rodrom\Advent202217\Test;

use PHPUnit\Framework\TestCase;
use Rodrom\Advent202217\Board;
use Rodrom\Advent202217\Piece;

class BoardTest extends TestCase
{
    public function test_create_board(): void
    {
        $board = new Board();
        $this->assertInstanceOf(Board::class, $board);

        $this->assertContainsOnlyInstancesOf(\SplStack::class, $board->columns);
    }

    public function test_set_sequence_of_rocks(): void
    {
        $simplePiece = new Piece(1,1, [[0][0] => "#"], 0);
        $board = new Board();

        $board->setPiecesSequence([$simplePiece]);

        $this->assertContainsOnly(Piece::class, $board->sequence);
    }

    public function test_play_first_piece_the_top(): void
    {
        $simplePiece = new Piece(1,1, [[0][0] => "#"], 0);
        $board = new Board();

        $board->setPiecesSequence([$simplePiece]);
        $board->setJetsSequence("><");

        $position = $board->playPieceAtTheTop();

        $this->assertEquals(['x' => 3, 'y' => 1], $position);
        $this->assertEquals(1, $board->columns[3]->top());
        $this->assertEquals(1, $board->getTowerHeight());
    }

    public function test_play_two_turns_with_one_simple_piece(): void
    {
        $simplePiece = new Piece(1,1, [[0][0] => "#"], 0);
        $board = new Board();

        $board->setPiecesSequence([$simplePiece]);
        $board->setJetsSequence("><");

        $position = $board->playPieceAtTheTop();

        $this->assertEquals(['x' => 3, 'y' => 1], $position);
        $this->assertEquals(1, $board->columns[3]->top());
        $this->assertEquals(1, $board->getTowerHeight());

        $position = $board->playPieceAtTheTop();

        $this->assertEquals(['x' => 2, 'y' => 1], $position);
        $this->assertEquals(1, $board->getTowerHeight());
        $this->assertEquals(0, $board->columns[0]->top());
        $this->assertEquals(0, $board->columns[1]->top());
        $this->assertEquals(1, $board->columns[2]->top());
        $this->assertEquals(1, $board->columns[3]->top());
        $this->assertEquals(0, $board->columns[4]->top());
        $this->assertEquals(0, $board->columns[5]->top());
        $this->assertEquals(0, $board->columns[6]->top());
    }

    public function test_play_cross_piece(): void
    {
        $board = new Board(4, 0, 2, 0);
        $crossPiece = new Piece(
            height: 3,
            width: 3,
            form: [
                0 => [ -2 => '.', -1 => '#', 0 => '.'],
                1 => [ -2 => '#', -1 => '#', 0 => '#'],
                2 => [ -2 => '.', -1 => '#', 0 => '.']
            ],
            id: 0
        );

        $board->columns[2]->push(1);
        //$board->columns[1]->push(0);
        //$board->columns[2]->push(1);
        // x = 0 y = 1 is filled.
        $position = [ 'x' => 0, 'y' => 1];
        $this->assertTrue($board->isTouchingDown($crossPiece, $position));

        //$board->setJetsSequence("<<");
        /* $position = $board->playPieceAtTheTop();
        $this->assertEquals(['x' => 0, 'y' => 1], $position);
        $this->assertEquals(2, $board->columns[0]->top());
        $this->assertEquals(3, $board->columns[1]->top());
        $this->assertEquals(2, $board->columns[2]->top());
        $this->assertContains(1, $board->columns[0]);
        $this->assertContains(1, $board->columns[1]); */
    }

    public function test_tricky_cross_move_under_top_height(): void
    {
        $board = new Board(4, 0, 2, 0);
        $board->setJetsSequence("<<<<<<>>");

        $crossPiece = new Piece(
            height: 3,
            width: 3,
            form: [
                0 => [ -2 => '.', -1 => '#', 0 => '.'],
                1 => [ -2 => '#', -1 => '#', 0 => '#'],
                2 => [ -2 => '.', -1 => '#', 0 => '.']
            ],
            id: 0
        );

        $board->setPiecesSequence([$crossPiece]);

        $board->columns[3]->push(1);
        $board->columns[3]->push(4);

        $position = [ 'x' => 0, 'y' => 3];
        $this->assertFalse($board->isSpaceRight($crossPiece, $position));
        $this->assertFalse($board->isSpaceLeft($crossPiece, $position));
        $this->assertFalse($board->isTouchingDown($crossPiece, $position));

        $position = [ 'x' => 0, 'y' => 2];
        $this->assertTrue($board->isSpaceRight($crossPiece, $position));
        $this->assertFalse($board->isSpaceLeft($crossPiece, $position));
        $this->assertFalse($board->isTouchingDown($crossPiece, $position));

        $position = [ 'x' => 1, 'y' => 2];
        $this->assertFalse($board->isSpaceRight($crossPiece, $position));
        $this->assertTrue($board->isSpaceLeft($crossPiece, $position));
        $this->assertFalse($board->isTouchingDown($crossPiece, $position));

        $position = [ 'x' => 1, 'y' => 1];
        $this->assertFalse($board->isSpaceRight($crossPiece, $position));
        $this->assertTrue($board->isSpaceLeft($crossPiece, $position));
        $this->assertTrue($board->isTouchingDown($crossPiece, $position));

        // $board->setPieceDown($crossPiece, $position);
        $board->playPieceAtTheTop();

        $this->assertCount(4, $board->columns[3]);
        $this->assertEquals(4, $board->columns[3]->pop());
        $this->assertEquals(2, $board->columns[3]->pop());
        $this->assertEquals(1, $board->columns[3]->pop());

        $this->assertCount(4, $board->columns[2]);
        $this->assertEquals(3, $board->columns[2]->pop());
        $this->assertEquals(2, $board->columns[2]->pop());
        $this->assertEquals(1, $board->columns[2]->pop());

        $this->assertCount(2, $board->columns[1]);
        $this->assertEquals(2, $board->columns[1]->pop());

        $this->assertCount(1, $board->columns[0]);

    }

    public function test_example(): void
    {
        $rocks = [
            0 => new Piece(
                height: 1,
                width: 4,
                form: [ 
                    [0][0] => '#', [1][0] => '#', [2][0] => '#', [3][0] => '#'
                ],
                id: 0
            ),
            1 => new Piece(
                height: 3,
                width: 3,
                form: [
                    0 => [ -2 => '.', -1 => '#', 0 => '.'],
                    1 => [ -2 => '#', -1 => '#', 0 => '#'],
                    2 => [ -2 => '.', -1 => '#', 0 => '.']
                ],
                id: 1
            ),
            2 => new Piece(
                height: 3,
                width: 3,
                form: [
                    0 => [ -2 => '.', -1 => '.', 0 => '#'],
                    1 => [ -2 => '.', -1 => '.', 0 => '#'],
                    2 => [ -2 => '#', -1 => '#', 0 => '#']
                ],
                id: 2
            ),
            3 => new Piece(
                height: 4,
                width: 1,
                form: [
                    0 => [-3 => '#', -2 => '#', -1 => '#', 0 => '#']
                ],
                id: 3
            ),
            4 => new Piece(
                height: 2,
                width: 2,
                form: [
                    0 => [ -1 => '#', 0 => '#' ],
                    1 => [ -1 => '#', 0 => '#' ],
                ],
                id: 4
            ),
        ];
        $board = new Board();

        $board->setPiecesSequence($rocks);

        $board->setJetsSequence(">>><<><>><<<>><>>><<<>>><<<><<<>><>><<>>");

        for ($i = 0; $i < 10; $i++) {
            $board->playPieceAtTheTop();
        }

        $expected = <<<EOD
        |....#..|16
        |....#..|15
        |....##.|14
        |##..##.|13
        |######.|12
        |.###...|11
        |..#....|10
        |.####..|9
        |....##.|8
        |....##.|7
        |....#..|6
        |..#.#..|5
        |..#.#..|4
        |#####..|3
        |..###..|2
        |...#...|1
        |..####.|0

        EOD;

        $this->assertEquals($expected, $board->__toString());
    }

    public function test_tricky_flat_move_under_top_height(): void
    {
        $board = new Board(5, 0, 0, 0);
        $board->setJetsSequence("<>>>");

        $piece = new Piece(
            height: 1,
            width: 4,
            form: [ 
                [0][0] => '#', [1][0] => '#', [2][0] => '#', [3][0] => '#'
            ],
            id: 0
        );

        $board->setPiecesSequence([$piece]);

        $board->columns[4]->push(1);
        $board->columns[4]->push(4);

        $position = [ 'x' => 0, 'y' => 4];
        $this->assertFalse($board->isSpaceRight($piece, $position));
        $this->assertFalse($board->isSpaceLeft($piece, $position));
        $this->assertFalse($board->isTouchingDown($piece, $position));

        $position = [ 'x' => 0, 'y' => 3];
        $this->assertTrue($board->isSpaceRight($piece, $position));
        $this->assertFalse($board->isSpaceLeft($piece, $position));
        $this->assertFalse($board->isTouchingDown($piece, $position));

        $position = [ 'x' => 1, 'y' => 3];
        $this->assertFalse($board->isSpaceRight($piece, $position));
        $this->assertTrue($board->isSpaceLeft($piece, $position));
        $this->assertFalse($board->isTouchingDown($piece, $position));

        $position = [ 'x' => 1, 'y' => 2];
        $this->assertFalse($board->isSpaceRight($piece, $position));
        $this->assertTrue($board->isSpaceLeft($piece, $position));
        $this->assertTrue($board->isTouchingDown($piece, $position));

        // $board->setPieceDown($piece, $position);
        $board->playPieceAtTheTop();
        $output = $board->__toString();
        $expected = <<<EOD
        |....#|4
        |.....|3
        |.####|2
        |....#|1
        |.....|0

        EOD;
        $this->assertEquals($expected, $output);
    }

    public function test_tricky_inverted_L_move_under_top_height(): void
    {
        $board = new Board(4, 0, 0, 0);
        $board->setJetsSequence("<<>>>");

        $piece = new Piece(
            height: 3,
            width: 3,
            form: [
                0 => [ -2 => '.', -1 => '.', 0 => '#'],
                1 => [ -2 => '.', -1 => '.', 0 => '#'],
                2 => [ -2 => '#', -1 => '#', 0 => '#']
            ],
            id: 0
        );

        $board->setPiecesSequence([$piece]);

        $board->columns[3]->push(0);
        $board->columns[3]->push(4);

        $position = [ 'x' => 0, 'y' => 4];
        $this->assertFalse($board->isSpaceRight($piece, $position));
        $this->assertFalse($board->isSpaceLeft($piece, $position));
        $this->assertFalse($board->isTouchingDown($piece, $position));

        $position = [ 'x' => 0, 'y' => 3];
        $this->assertFalse($board->isSpaceRight($piece, $position));
        $this->assertFalse($board->isSpaceLeft($piece, $position));
        $this->assertFalse($board->isTouchingDown($piece, $position));

        $position = [ 'x' => 0, 'y' => 2];
        $this->assertFalse($board->isSpaceRight($piece, $position));
        $this->assertFalse($board->isSpaceLeft($piece, $position));
        $this->assertFalse($board->isTouchingDown($piece, $position));

        $position = [ 'x' => 0, 'y' => 1];
        $this->assertTrue($board->isSpaceRight($piece, $position));
        $this->assertFalse($board->isSpaceLeft($piece, $position));
        $this->assertFalse($board->isTouchingDown($piece, $position));

        $position = [ 'x' => 1, 'y' => 1];
        $this->assertFalse($board->isSpaceRight($piece, $position));
        $this->assertTrue($board->isSpaceLeft($piece, $position));
        $this->assertTrue($board->isTouchingDown($piece, $position));

        $board->playPieceAtTheTop();
        $output = $board->__toString();
        $expected = <<<EOD
        |...#|4
        |...#|3
        |...#|2
        |.###|1
        |...#|0

        EOD;
        $this->assertEquals($expected, $output);
    }

    public function test_tricky_vertical_move_under_top_height(): void
    {
        $board = new Board(2, 0, 0, 0);
        $board->setJetsSequence("<>>>>>");

        $piece = new Piece(
            height: 4,
            width: 1,
            form: [
                0 => [-3 => '#', -2 => '#', -1 => '#', 0 => '#']
            ],
            id: 3
        );

        $board->setPiecesSequence([$piece]);

        $board->columns[1]->push(0);
        $board->columns[1]->push(5);

        $position = [ 'x' => 0, 'y' => 4];
        $this->assertFalse($board->isSpaceRight($piece, $position));
        $this->assertFalse($board->isSpaceLeft($piece, $position));
        $this->assertFalse($board->isTouchingDown($piece, $position));

        $position = [ 'x' => 0, 'y' => 3];
        $this->assertFalse($board->isSpaceRight($piece, $position));
        $this->assertFalse($board->isSpaceLeft($piece, $position));
        $this->assertFalse($board->isTouchingDown($piece, $position));

        $position = [ 'x' => 0, 'y' => 2];
        $this->assertFalse($board->isSpaceRight($piece, $position));
        $this->assertFalse($board->isSpaceLeft($piece, $position));
        $this->assertFalse($board->isTouchingDown($piece, $position));

        $position = [ 'x' => 0, 'y' => 1];
        $this->assertTrue($board->isSpaceRight($piece, $position));
        $this->assertFalse($board->isSpaceLeft($piece, $position));
        $this->assertFalse($board->isTouchingDown($piece, $position));

        $position = [ 'x' => 1, 'y' => 1];
        $this->assertFalse($board->isSpaceRight($piece, $position));
        $this->assertTrue($board->isSpaceLeft($piece, $position));
        $this->assertTrue($board->isTouchingDown($piece, $position));

        $board->playPieceAtTheTop();
        $output = $board->__toString();
        $expected = <<<EOD
        |.#|5
        |.#|4
        |.#|3
        |.#|2
        |.#|1
        |.#|0

        EOD;
        $this->assertEquals($expected, $output);
    }

    public function test_tricky_square_move_under_top_height(): void
    {
        $board = new Board(3, 0, 0, 0);
        $board->setJetsSequence("<<>>>");

        $piece = new Piece(
            height: 2,
            width: 2,
            form: [
                0 => [ -1 => '#', 0 => '#' ],
                1 => [ -1 => '#', 0 => '#' ],
            ],
            id: 0
        );

        $board->setPiecesSequence([$piece]);

        $board->columns[2]->push(1);
        $board->columns[2]->push(4);

        $position = [ 'x' => 0, 'y' => 4];
        $this->assertFalse($board->isSpaceRight($piece, $position));
        $this->assertFalse($board->isSpaceLeft($piece, $position));
        $this->assertFalse($board->isTouchingDown($piece, $position));

        $position = [ 'x' => 0, 'y' => 3];
        $this->assertFalse($board->isSpaceRight($piece, $position));
        $this->assertFalse($board->isSpaceLeft($piece, $position));
        $this->assertFalse($board->isTouchingDown($piece, $position));

        $position = [ 'x' => 0, 'y' => 2];
        $this->assertTrue($board->isSpaceRight($piece, $position));
        $this->assertFalse($board->isSpaceLeft($piece, $position));
        $this->assertFalse($board->isTouchingDown($piece, $position));

        $position = [ 'x' => 1, 'y' => 2];
        $this->assertFalse($board->isSpaceRight($piece, $position));
        $this->assertTrue($board->isSpaceLeft($piece, $position));
        $this->assertTrue($board->isTouchingDown($piece, $position));

        $board->playPieceAtTheTop();
        $output = $board->__toString();
        $expected = <<<EOD
        |..#|4
        |.##|3
        |.##|2
        |..#|1
        |...|0

        EOD;
        $this->assertEquals($expected, $output);
    }

    public function test_tricky_left_cross_move_under_top_height(): void
    {
        $board = new Board(4, 0, 0, 0);
        $board->setJetsSequence(">><<<<");

        $crossPiece = new Piece(
            height: 3,
            width: 3,
            form: [
                0 => [ -2 => '.', -1 => '#', 0 => '.'],
                1 => [ -2 => '#', -1 => '#', 0 => '#'],
                2 => [ -2 => '.', -1 => '#', 0 => '.']
            ],
            id: 0
        );

        $board->setPiecesSequence([$crossPiece]);

        $board->columns[0]->push(2);
        $board->columns[0]->push(4);

        $position = [ 'x' => 1, 'y' => 3];
        $this->assertFalse($board->isSpaceRight($crossPiece, $position));
        $this->assertFalse($board->isSpaceLeft($crossPiece, $position));
        $this->assertFalse($board->isTouchingDown($crossPiece, $position));

        $position = [ 'x' => 1, 'y' => 2];
        $this->assertFalse($board->isSpaceRight($crossPiece, $position));
        $this->assertTrue($board->isSpaceLeft($crossPiece, $position));
        $this->assertFalse($board->isTouchingDown($crossPiece, $position));

        $position = [ 'x' => 1, 'y' => 1];
        $this->assertFalse($board->isSpaceRight($crossPiece, $position));
        $this->assertFalse($board->isSpaceLeft($crossPiece, $position));
        $this->assertFalse($board->isTouchingDown($crossPiece, $position));

        $position = [ 'x' => 0, 'y' => 2];
        $this->assertTrue($board->isSpaceRight($crossPiece, $position));
        $this->assertFalse($board->isSpaceLeft($crossPiece, $position));
        $this->assertTrue($board->isTouchingDown($crossPiece, $position));

        $board->playPieceAtTheTop();

        $output = $board->__toString();
        $expected = <<<EOD
        |##..|4
        |###.|3
        |##..|2
        |....|1
        |....|0

        EOD;
        $this->assertEquals($expected, $output);
    }

    public function test_tricky_left_square_move_under_top_height(): void
    {
        $board = new Board(4, 2, 0, 0);
        $board->setJetsSequence(">><<<<");

        $piece = new Piece(
            height: 2,
            width: 2,
            form: [
                0 => [ -1 => '#', 0 => '#' ],
                1 => [ -1 => '#', 0 => '#' ],
            ],
            id: 0
        );

        $board->setPiecesSequence([$piece]);

        $board->columns[0]->push(0);
        $board->columns[0]->push(3);
        $board->columns[0]->push(4);
        $board->columns[1]->push(4);

        $board->playPieceAtTheTop();

        $output = $board->__toString();
        $expected = <<<EOD
        |##..|4
        |#...|3
        |##..|2
        |##..|1
        |#...|0

        EOD;
        $this->assertEquals($expected, $output);
    }

    public function test_tricky_left_inverted_L_move_under_top_height(): void
    {
        $board = new Board(5, 2, 0, 0);
        $board->setJetsSequence(">><<<<");

        $piece = new Piece(
            height: 3,
            width: 3,
            form: [
                0 => [ -2 => '.', -1 => '.', 0 => '#'],
                1 => [ -2 => '.', -1 => '.', 0 => '#'],
                2 => [ -2 => '#', -1 => '#', 0 => '#']
            ],
            id: 0
        );

        $board->setPiecesSequence([$piece]);

        $board->columns[0]->push(0);
        $board->columns[0]->push(4);
        $board->columns[1]->push(4);

        $board->playPieceAtTheTop();

        $output = $board->__toString();
        $expected = <<<EOD
        |##...|4
        |..#..|3
        |..#..|2
        |###..|1
        |#....|0

        EOD;
        $this->assertEquals($expected, $output);
    }
}
