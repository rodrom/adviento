<?php

declare(strict_types=1);
namespace Rodrom\Advent202217\Test;

use PHPUnit\Framework\TestCase;
use Rodrom\Advent202217\Piece;

class PieceTest extends TestCase
{
    public function test_simple_piece_checkings(): void
    {
        $piece = new Piece(1, 1, [[0][0] => '#'], 0);

        $this->assertEquals(0, $piece->bottom(0));
        $this->assertEquals(0, $piece->left(0));
        $this->assertEquals(0, $piece->right(0));
    }

    public function test_horizontal_piece_checkings(): void
    {
        $piece =  new Piece(
            height: 1,
            width: 4,
            form: [ 
                [0][0] => '#', [1][0] => '#', [2][0] => '#', [3][0] => '#'
            ],
            id: 0
        );

        $this->assertEquals(0, $piece->bottom(0));
        $this->assertEquals(0, $piece->bottom(1));
        $this->assertEquals(0, $piece->bottom(2));
        $this->assertEquals(0, $piece->bottom(3));
        $this->assertEquals(0, $piece->left(0));
        $this->assertEquals(3, $piece->right(0));
    }

    public function test_cross_piece_checkings(): void
    {
        $form = [
            0 => [ -2 => '.', -1 => '#', 0 => '.'],
            1 => [ -2 => '#', -1 => '#', 0 => '#'],
            2 => [ -2 => '.', -1 => '#', 0 => '.']
        ];
        
        $piece = new Piece(
            height: 3,
            width: 3,
            form: $form,
            id: 1
        );
        $this->assertEquals(-1, $piece->bottom(0));
        $this->assertEquals(0, $piece->bottom(1));
        $this->assertEquals(-1, $piece->bottom(2));

        $this->assertEquals(1, $piece->left(0));
        $this->assertEquals(0, $piece->left(-1));
        $this->assertEquals(1, $piece->left(-2));

        $this->assertEquals(1, $piece->right(0));
        $this->assertEquals(2, $piece->right(-1));
        $this->assertEquals(1, $piece->right(-2));
    }

    public function test_inverted_L_piece_checkings(): void
    {
        $form = [
            0 => [ -2 => '.', -1 => '.', 0 => '#'],
            1 => [ -2 => '.', -1 => '.', 0 => '#'],
            2 => [ -2 => '#', -1 => '#', 0 => '#']
        ];
        
        $piece = new Piece(
            height: 3,
            width: 3,
            form: $form,
            id: 2
        );
        $this->assertEquals(0, $piece->bottom(0));
        $this->assertEquals(0, $piece->bottom(1));
        $this->assertEquals(0, $piece->bottom(2));

        $this->assertEquals(0, $piece->left(0));
        $this->assertEquals(2, $piece->left(-1));
        $this->assertEquals(2, $piece->left(-2));

        $this->assertEquals(2, $piece->right(0));
        $this->assertEquals(2, $piece->right(-1));
        $this->assertEquals(2, $piece->right(-2));
    }

    public function test_square_piece_checkings(): void
    {
        $form = [
            0 => [ -1 => '#', 0 => '#' ],
            1 => [ -1 => '#', 0 => '#' ],
        ];
        
        $piece = new Piece(
            height: 2,
            width: 2,
            form: $form,
            id: 2
        );
        $this->assertEquals(0, $piece->bottom(0));
        $this->assertEquals(0, $piece->bottom(1));


        $this->assertEquals(0, $piece->left(0));
        $this->assertEquals(0, $piece->left(-1));

        $this->assertEquals(1, $piece->right(0));
        $this->assertEquals(1, $piece->right(-1));
    }

    public function test_vertical_piece_checkings(): void
    {
        $piece =  new Piece(
            height: 4,
            width: 1,
            form: [ 
                0 => [-3 => '#', -2 => '#', -1 => '#', 0 => '#']
            ],
            id: 4
        );

        $this->assertEquals(0, $piece->bottom(0));

        $this->assertEquals(0, $piece->left(0));
        $this->assertEquals(0, $piece->left(-1));
        $this->assertEquals(0, $piece->left(-2));
        $this->assertEquals(0, $piece->left(-3));

        $this->assertEquals(0, $piece->right(0));
        $this->assertEquals(0, $piece->right(-1));
        $this->assertEquals(0, $piece->right(-2));
        $this->assertEquals(0, $piece->right(-3));
    }
}
