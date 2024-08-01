<?php

declare(strict_types=1);
namespace Rodrom\Advent202207;

class Prompt
{
    public Folder $pwd;
    public array $history;
    public array $input;
    public int $currentInputLineNumber;
    
    public function __construct(Folder $pwd)
    {
        $this->pwd = $pwd;
        $this->history = [];
    }

    public function loadInput(array $input, int $currentLine = 0): void
    {
        $this->input = $input;
        $this->currentInputLineNumber = $currentLine;
    }

    public function readCommand(string|null $command = null): bool
    {
        if (!$command) {
            if ($this->currentInputLineNumber >= count($this->input)) {
                return false;    
            }
            $command = $this->input[$this->currentInputLineNumber];
        }

        $result = false;
        if (preg_match('/^\$ cd \/$/', $command)) {
            $result = $this->changeToRootFolder();
            $this->history[] = $command;
            $this->currentInputLineNumber++;
        } elseif (preg_match('/^\$ cd (\w+)$/', $command, $results)) {
            $result = $this->changeDirectory($results[1]);
            $this->history[] = $command;
            $this->currentInputLineNumber++;
        } elseif (preg_match('/^\$ cd \.\.$/', $command)) {
            $result = $this->changeToParentDirectory();
            $this->history[] = $command;
            $this->currentInputLineNumber++;
        } elseif (preg_match('/^\$ ls$/', $command)) {
            $result = $this->readListCommandOutput();
            $this->history[] = $command;
        }

        return $result;
    }

    public function changeDirectory(string $destination): bool
    {
        foreach($this->pwd->sons() as $son) {
            if ($son->name() === $destination) {
                $this->pwd = $son;
                return true;
            }
        }
        return false;
    }

    public function changeToRootFolder(): bool {
        while ($this->pwd->parent()) {
            $this->pwd = $this->pwd->parent();
        }
        return true;
    }

    public function changeToParentDirectory(): bool
    {
        if ($this->pwd->parent()) {
            $this->pwd = $this->pwd->parent();
            return true;
        }
        return false;
    }

    public function readListCommandOutput(): bool
    {
        $inputSize = count($this->input);
        $this->currentInputLineNumber++;
        $result = false;
        while ($inputSize > $this->currentInputLineNumber) {
            $current = $this->input[$this->currentInputLineNumber];
            if (preg_match('/^\$ .*$/', $current)) {
                break;
            }
            if (preg_match('/^(\d+) (\w+|\w+\.\w{3})$/', $current, $matches)) {
                $file = new File($this->pwd, $matches[2], intval($matches[1]));
                $result = true;
            } elseif (preg_match('/^dir (\w+)$/', $current, $matches)) {
                $dir = new Folder($this->pwd, $matches[1]);
                $result = true;
            } else {
                throw new \Exception("Error readin input");
            }
            $this->currentInputLineNumber++;
        }
        return $result;
    }
}
