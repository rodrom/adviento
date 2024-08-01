<?php

declare(strict_types=1);
namespace Rodrom\Advent202208\Tests;

use PHPUnit\Framework\TestCase;
use Rodrom\Advent202208\Grid;

class TsrTest extends TestCase
{
    public function testTsrGridOfOne(): void
    {
        $grid = Grid::loadMap("1");
        
        $this->assertEquals(0, $grid->treeSceneryRating(0,0));
        $this->assertEquals(0, $grid->maxTsr());
    }

    public function testTsrGridOf3SmallerInTheMiddle(): void
    {
        $grid = Grid::loadMap(["222","212","222"]);
        
        $this->assertEquals(1, $grid->treeSceneryRating(1,1));
    }
    
    public function testTsrGridOf3TallerInTheMiddle(): void
    {
        $grid = Grid::loadMap(["222","232","222"]);
        
        $this->assertEquals(1, $grid->treeSceneryRating(1,1));
        $this->assertEquals(0, $grid->treeSceneryRating(0,1));
        $this->assertEquals(1, $grid->maxTsr());
    }

    public function testTsrExample(): void
    {
        $map = [
            "30373",
            "25512",
            "65332",
            "33549",
            "35390",
        ];

        $grid = Grid::loadMap($map);
        
        $this->assertEquals(4, $grid->treeSceneryRating(1,2));
        $this->assertEquals(8, $grid->treeSceneryRating(3,2));
        $this->assertEquals(8, $grid->maxTsr());
    }
}