<?php

declare(strict_types=1);
namespace Rodrom\Advent202216;
use Tree\Node\Node;

class ActionTree
{
    

    public function __construct(
        public Node|null $root = null,
        public Node|null $parent = null,
        public Node|null $current = null
    ) { }

    public function setRoot(Action $action): Node
    {
        $this->root = new Node($action);
        $this->parent = null;
        $this->current = $this->root;
        
        return $this->root;
    }

    public function insertChild(Action $action, Node $parent): Node
    {
        $this->parent = $parent;
        $this->parent->addChild($this->current = new Node($action));
        return $this->current;
    }
}