<?php

declare(strict_types=1);

namespace Rodrom\Advent202207\Tests;
use PHPUnit\Framework\TestCase;
use Rodrom\Advent202207\Folder;
use Rodrom\Advent202207\Prompt;

class PromptTest extends TestCase
{
    public function testCreatePrompt(): void
    {
        $mockRootFolder = $this->createMock(Folder::class);
        $prompt = new Prompt($mockRootFolder);
        $this->assertInstanceOf(Prompt::class, $prompt);
    }

    public function testReadCommandCdRootFolder(): void
    {
        $root = new Folder(null, "/");
        $prompt = new Prompt($root);
        $prompt->loadInput(['$ cd /']);
        
        $this->assertTrue($prompt->readCommand());
        $this->assertEquals($prompt->pwd, $root);
        $this->assertContains('$ cd /', $prompt->history);
    }

    public function testloadInput(): void
    {
        $root = new Folder(null, "/");
        $prompt = new Prompt($root);
        $prompt->loadInput([
            "$ ls ",
            "10 file1",
        ]);

        $this->assertContains("10 file1", $prompt->input);
        $this->assertEquals(0, $prompt->currentInputLineNumber);
    }

    public function testLsCommandInRootFolderAddFile(): void
    {
        $root = new Folder(null, "/");
        $prompt = new Prompt($root);
        $prompt->loadInput([
            "$ ls",
            "10 file1",
        ]);

        $this->assertTrue($prompt->readCommand($prompt->input[0]));
        $this->assertCount(1, $root->sons());
    }
}
