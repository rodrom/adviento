<?php

declare(strict_types=1);

namespace Rodrom\Advent202208\Tests;

use PHPUnit\Framework\TestCase;
use Rodrom\Advent202208\Grid;

class GridTest extends TestCase
{

    public function testCreateGridOneDimension(): void
    {
        $grid = Grid::loadMap("1");
        $this->assertInstanceOf(Grid::class, $grid);
        $this->assertEquals(1, $grid->getTree(0,0));
        $this->assertEquals(1, $grid->countTrees());
        $this->assertTrue($grid->isAtTheEdges(0,0));
        $this->assertEquals(1, $grid->visibleTrees());
    }

    public function testCreateGridTwoDimension(): void
    {
        $grid = Grid::loadMap(["11","11"]);
        $this->assertInstanceOf(Grid::class, $grid);
        $this->assertEquals(1, $grid->getTree(0,0));
        $this->assertEquals(4, $grid->countTrees());
        $this->assertTrue($grid->isAtTheEdges(0,0), "ERROR 0,0");
        $this->assertTrue($grid->isAtTheEdges(1,1), "ERROR 2,2");
        $this->assertEquals(4, $grid->visibleTrees());
    }

    public function testCreateGridThreeDimension(): void
    {
        $grid = Grid::loadMap(["222","212","222"]);
        $this->assertInstanceOf(Grid::class, $grid);
        $this->assertEquals(2, $grid->getTree(0,0));
        $this->assertEquals(9, $grid->countTrees());
        $this->assertTrue($grid->isAtTheEdges(0,0), "ERROR 0,0");
        $this->assertFalse($grid->isAtTheEdges(1,1), "ERROR 2,2");
        $this->assertEquals(8, $grid->visibleTrees());
    }

    public function testIsVisibleFunctionsFalseResults(): void
    {
        $grid = Grid::loadMap(["222","212","222"]);
        $this->assertFalse($grid->isVisibleFromLeft(1,1));
        $this->assertFalse($grid->isVisibleFromRight(1,1));
        $this->assertFalse($grid->isVisibleFromTop(1,1));
        $this->assertFalse($grid->isVisibleFromBottom(1,1));
    }

    public function testIsVisibleFunctionsTrueResults(): void
    {
        $grid = Grid::loadMap(["222","232","222"]);
        $this->assertTrue($grid->isVisibleFromLeft(1,1));
        $this->assertTrue($grid->isVisibleFromRight(1,1));
        $this->assertTrue($grid->isVisibleFromTop(1,1));
        $this->assertTrue($grid->isVisibleFromBottom(1,1));
    }

    public function testExampleGrid(): void
    {
        
        $map = ["30373",
                "25512",
                "65332",
                "33549",
                "35390",
        ];
        
        $grid = Grid::loadMap($map);
        $this->assertInstanceOf(Grid::class, $grid);
        $this->assertEquals(3, $grid->getTree(0,0));
        $this->assertEquals(25, $grid->countTrees());
        $this->assertTrue($grid->isAtTheEdges(0,4), "ERROR 0,0");
        $this->assertTrue($grid->isAtTheEdges(4,4), "ERROR 5,5");
        $this->assertEquals(21, $grid->visibleTrees());
    }

}