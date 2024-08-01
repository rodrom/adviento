<?php

declare(strict_types=1);
namespace Rodrom\Advent202207\Tests;

use OutOfRangeException;
use PHPUnit\Framework\TestCase;
use Rodrom\Advent202207\File;
use Rodrom\Advent202207\Folder;
use Rodrom\Advent202207\FolderRecursiveIterator;

class FolderRecursiveIteratorTest extends TestCase
{
    private Folder $root;
    private FolderRecursiveIterator $it;

     protected function setUp(): void
    {
        $this->root = new Folder(null, "/");
        $this->it = new FolderRecursiveIterator($this->root);
    }

    public function testCreateFolderRecursiveIterator(): void
    {
        $this->assertInstanceOf(FolderRecursiveIterator::class, $this->it);
    }

    public function testExpectOutOfRangeExceptionInEmptyFolder(): void
    {
        $this->expectException(OutOfRangeException::class);
        $this->it->current();
    }

    public function testIterationWithOneFile(): void
    {
        $file = new File($this->root, "unfichero", 10);
        foreach ($this->root as $f) {
            $this->assertEquals($file, $f);
        }
    }

    public function testIterationWithRecursiveEmptyFolder(): void
    {
        $dir = new Folder($this->root, "directorio");
        foreach ($this->root as $fsnode) {
            $this->assertEquals($dir, $fsnode);
        }
    }

    public function testIterationWithRecursiveNonEmptyFolder(): void
    {
        $expected[] = new Folder($this->root, "directorio");
        $expected[] = new File($expected[0], "fichero", 10);

        foreach ($this->root as $fsnode) {
            $this->assertEquals(array_shift($expected), $fsnode);
        }
    }
}