# Day 7: No Space Left On Device

The input data is a list of commands and their outputs in the device system.
The objective is simulate the instructions in the device, and analyse which are the
total sizes of the folders.

The commands begin with `$`, for example: `$ cd /` or `$ ls`.
For `cd`:
- `cd x` moves one level into `x` folder.
- `cd ..` moves out one level.
- `cd /` goes to the root folder

`ls` list files and directories:
- `123 abc` means that the current directory contains a file named `abc` with size `123`.
- `dir xyz` means that the current directory contains a file named `xyz`.

The first version of the program should count all folders with a total size at most 100000. And give the sum.
It is possible to count the same file several times.

### Solution

The program should keep track of the filesystem in some way. We can consider files and folders like tree structure that have a **parent**, which is another folder.
Only `/` folder have no parent (or null parent).
Files have also a property of size.
Files and folders have also a name.

```php
abstract class FSNode
{
    protected Folder|null $parent;
    protected string $name;

    public function __construct(Folder|null $parent = null, string $name)
    {
        $this->parent = $parent;
        $this->name = $name;
    }
}

class Folder extends FSNode
{
    protected \Ds\Set $sons;
    
    // ...
}

class File extends FSNode
{
    protected int $size;

    public function __construct(Folder|null $parent, string $name, int $size)
    {
        parent::__construct($parent, $name);
        $this->size = $size;
    }

    // ***
}
```

The interpreter of the input could be a **Prompt** that runs the **commands** and read the output according to the command.
Could have also a property call **pwd** that keep track of the current folder. And could be useful have a history of commands.
The program should create the root folder first.
Then should create the **prompt**. The prompt read the first command and execute it. In every case, this first command should be go to the root folder.
If the line give is not a command, it should throw and exception.
The function `readCommand` check that the line starts with `$`. And then read check the first word, that indicates the command, if necessary also, gets the attributes and options.

```php
class Prompt {
    private Folder $pwd;
    private array $history;

    public function readCommand(string $command): bool
    {
        if (preg_match("/^\$ cd (\w+)$/", $command, $results)) {
            // change directory
        }
    }
}
```

After saving all the input data inside this structures, the problem ask for calc the total sum of files, including subdirectories.
To do this operation, one option is return the size of each folder. If the folder have subfolders, the method recursively call it in sons folder.

Now, the problem also ask for filter directories that have a size bigger than X.
We can implement a class call `FolderSizeFilter` that iterates