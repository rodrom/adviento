<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Rodrom\Advent202218\Block;
use Rodrom\Advent202218\Graph;
use Rodrom\Advent202218\Scan3d;

class Scan3dTest extends TestCase
{
    public function test_create_simple_scan3d(): void
    {
        $scan = new Scan3d();

        $this->assertInstanceOf(Scan3d::class, $scan);
        $this->assertEmpty($scan->blocks);
    }

    public function test_read_example_input(): void
    {
        $scan = Scan3d::readInput(file_get_contents(__DIR__ . "\\..\\example.txt"));
    
        $this->assertInstanceOf(Scan3d::class, $scan);

        $this->assertCount(13, $scan->blocks);
        $this->assertContainsOnly(Block::class, $scan->blocks);
        $this->assertInstanceOf(Graph::class, $scan->graph);
    }

    public function test_create_simple_graph(): void
    {
        $scan = Scan3d::readInput("0,0,0\n0,0,1\n");

        $this->assertEquals(1, $scan->createGraph());

        $this->assertCount(2, $scan->graph->vertex);
        $this->assertCount(2, $scan->graph->edges);

    }

    public function test_count_faces_of_a_single_block(): void
    {
        $scan = Scan3d::readInput("0,0,0");
        $scan->createGraph();

        $this->assertEquals(6, $scan->surface());
    }

    public function test_surface_with_two_adjacent_blocks(): void
    {
        $scan = Scan3d::readInput("0,0,0\n0,0,1");
        $scan->createGraph();

        $this->assertEquals(10, $scan->surface());
    }

    public function test_inside(): void
    {
        $scan = Scan3d::readInput("0,0,1");
        
        $this->assertFalse($scan->inside(new Block(0,0,0)));
        $this->assertTrue($scan->inside(new Block(0,0,1)));
    }

    public function test_map3d(): void
    {
        $scan = Scan3d::readInput(
            "0,0,0\n1,0,0\n2,0,0\n" . 
            "0,1,0\n1,1,0\n2,1,0\n" .
            "0,2,0\n1,2,0\n2,2,0\n" .
            
            "0,0,1\n1,0,1\n2,0,1\n" . 
            "0,1,1\n1,1,1\n2,1,1\n" .
            "0,2,1\n1,2,1\n2,2,1\n" .

            "0,0,2\n1,0,2\n2,0,2\n" . 
            "0,1,2\n1,1,2\n2,1,2\n" .
            "0,2,2\n1,2,2\n2,2,2\n"
        );

        $this->assertCount(3, $scan->map3d[0]);
        $this->assertCount(3, $scan->map3d[0][0]);
        $this->assertIsInt($scan->map3d[0][0][0]);
        $this->assertEquals(26, $scan->map3d[2][2][2]);
    }

    public function test_block_of_air_in_full_lava_droplet_3x3x3(): void
    {
        $scan = Scan3d::readInput(
            "0,0,0\n1,0,0\n2,0,0\n" . 
            "0,1,0\n1,1,0\n2,1,0\n" .
            "0,2,0\n1,2,0\n2,2,0\n" .
            
            "0,0,1\n1,0,1\n2,0,1\n" . 
            "0,1,1\n1,1,1\n2,1,1\n" .
            "0,2,1\n1,2,1\n2,2,1\n" .

            "0,0,2\n1,0,2\n2,0,2\n" . 
            "0,1,2\n1,1,2\n2,1,2\n" .
            "0,2,2\n1,2,2\n2,2,2\n"
        );

        $result = $scan->blocksOfAir();

        $this->assertEquals(9 * 6, $result);
    }

    public function test_second_part_example(): void
    {
        $scan = Scan3d::readInput(file_get_contents(__DIR__ . "\\..\\example.txt"));

        $result = $scan->blocksOfAir();

        $this->assertEquals(58, $result);
    }
}