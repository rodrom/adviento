<?php

declare(strict_types=1);
namespace Rodrom\Advent202218\Test;

use PHPUnit\Framework\TestCase;
use Rodrom\Advent202218\Block;

class BlockTest extends TestCase
{
    public function test_create_block(): void
    {
        $block = new Block();

        $this->assertInstanceOf(Block::class, $block);
        $this->assertObjectHasAttribute('size', $block);
    }

    public function test_adjacent_blocks(): void
    {
        $p = new Block(0, 0, 0);
        $east = new Block(1, 0, 0);
        $north = new Block(0, 1, 0);
        $west = new Block(-1, 0, 0);
        $south = new Block(0, -1, 0);
        $back = new Block(0, 0, 1);
        $front = new Block(0, 0, -1);
        $southBack = new Block(0, -1, 1);
        $southFront = new Block(0, -1, - 1);


        $this->assertTrue(Block::adjacentBlocks($p, $east));
        $this->assertTrue(Block::adjacentBlocks($p, $north));
        $this->assertTrue(Block::adjacentBlocks($p, $west));
        $this->assertTrue(Block::adjacentBlocks($p, $south));
        $this->assertTrue(Block::adjacentBlocks($p, $back));
        $this->assertTrue(Block::adjacentBlocks($p, $front));

        $this->assertFalse(Block::adjacentBlocks($p, $southBack));
        $this->assertFalse(Block::adjacentBlocks($p, $southFront));

        $this->assertFalse(Block::adjacentBlocks($east, $west));
        $this->assertFalse(Block::adjacentBlocks($south, $north));
        $this->assertFalse(Block::adjacentBlocks($south, $east));
        $this->assertFalse(Block::adjacentBlocks($south, $west));
        $this->assertFalse(Block::adjacentBlocks($south, $front));
        $this->assertFalse(Block::adjacentBlocks($south, $back));

        $this->assertTrue(Block::adjacentBlocks($south, $southBack));
        $this->assertTrue(Block::adjacentBlocks($south, $southFront));
    }

    public function test_north_direction(): void
    {
        $p = new Block(0, 0, 0);
        $north = new Block(0, 1, 0);

        $this->assertEquals($north, Block::north($p));
    }

    public function test_south_direction(): void
    {
        $p = new Block(0, 0, 0);
        $south = new Block(0, -1, 0);

        $this->assertEquals($south, Block::south($p));
    }

    public function test_east_direction(): void
    {
        $p = new Block(0, 0, 0);
        $east = new Block(1, 0, 0);

        $this->assertEquals($east, Block::east($p));
    }

    public function test_west_direction(): void
    {
        $p = new Block(0, 0, 0);
        $west = new Block(-1, 0, 0);

        $this->assertEquals($west, Block::west($p));
    }

    public function test_front_direction(): void
    {
        $p = new Block(0, 0, 0);
        $front = new Block(0, 0, -1);

        $this->assertEquals($front, Block::front($p));
    }

    public function test_back_direction(): void
    {
        $p = new Block(0, 0, 0);
        $back = new Block(0, 0, 1);

        $this->assertEquals($back, Block::back($p));
    }

    public function test_limits(): void
    {
        $p = new Block(0,0,0);

        $this->assertTrue($p->limits(0,0));
        $this->assertTrue($p->limits(-1,1));
        $this->assertFalse($p->limits(1,2));
    }

    public function test_to_string(): void
    {
        $p = new Block(0, 0, 0);

        $this->assertEquals("(0, 0, 0)", $p->__toString());
        
        $q = new Block(-1, -1, -1);
        $this->assertEquals("(-1, -1, -1)", $q->__toString());
    }
}