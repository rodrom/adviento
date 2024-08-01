<?php

declare(strict_types=1);
namespace Rodrom\Advent202207\Tests;

use OutOfBoundsException;
use OutOfRangeException;
use PHPUnit\Framework\TestCase;
use Rodrom\Advent202207\File;
use Rodrom\Advent202207\Folder;
use Rodrom\Advent202207\FSNode;
use Traversable;

class FolderTest extends TestCase
{
    private Folder $root;

    protected function setUp(): void
    {
        $this->root = new Folder(null, "/");
    }
    
    public function testCreateRootFolder(): void
    {
        $this->assertInstanceOf(FSNode::class, $this->root);
        $this->assertInstanceOf(Folder::class, $this->root);
        $this->assertNull($this->root->parent());
    }

    public function testAddFolderToRootFolder(): void
    {
        $dir = new Folder($this->root, 'dir');
        $this->assertEquals($this->root, $dir->parent());
        $this->assertEquals("dir", $dir->name());
        $this->assertTrue($this->root->contains($dir));
    }

    public function testAddFileToRootFolder(): void
    {
        $file = new File($this->root, "fichero.txt", 10);
        $this->assertInstanceOf(File::class, $file);
        $this->assertInstanceOf(FSNode::class, $file);

        $this->assertEquals($this->root, $file->parent());
        $this->assertEquals("fichero.txt", $file->name());
        $this->assertEquals(10, $file->size());
        $this->assertTrue($this->root->contains($file));
        $this->assertEquals(10, $this->root->size());

        $file2 = new File($this->root, "fichero2", 30);
        $this->assertEquals(40, $this->root->size());
    }

    public function testSizeOfRootFolderWithSubfolders(): void
    {
        $file = new File($this->root, "fichero.txt", 10);
        $folderA = new Folder($this->root, "a");
        $fileInA = new File($folderA, "ficheroEnA", 20);

        $this->assertEquals(30, $this->root->size());
    }

    public function testFolderEmptyTrue(): void
    {
        $this->assertTrue($this->root->empty());
    }

    public function testFolderEmptyFalse(): void
    {
        $file = new File($this->root, "newfile.txt", 10);
        $this->assertFalse($this->root->empty());
    }

    public function testFolderGetSonEmptyFolder(): void
    {
        $this->expectException(OutOfRangeException::class);
        $this->root->getSon(0);
    }

    public function testFolderGetSon(): void
    {
        $file = new File($this->root, "newfile", 10);
        $this->assertEquals($file, $this->root->getSon(0));
    }

    public function testGetIterator(): void
    {
        $this->assertInstanceOf(Traversable::class, $this->root->getIterator());
    }
}
