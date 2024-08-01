<?php

declare(strict_types=1);

class Node
{
    public string $name;
    public Node|null $l;
    public Node|null $r;
    public string|int $v;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public static function InternalNode(string $name, string $op, Node $l, Node $r): static
    {
        $node = new static($name);
        $node->v = $op;
        $node->l = $l;
        $node->r = $r;
        
        return $node;
    }

    public static function Leaf(string $name, int $v): static
    {
        $node = new static($name);
        $node->v = $v;
        $node->l = null;
        $node->r = null;

        return $node;
    }
}

$input = array_map(
    function ($line) {
        if (preg_match('/^([a-z]{4}): ((([a-z]{4}) ([+\-*\/]) ([a-z]{4}))|(\d+))$/', $line, $matches)) {
            return strlen($matches[0]) === 17 ?
                [
                    'label' => $matches[1],
                    'left' => $matches[4],
                    'op' =>$matches[5],
                    'right' => $matches[6]
                ] 
                :
                [ 'label' => $matches[1], 'value' => intval($matches[2])];
        }
    },
    file($argv[1], FILE_IGNORE_NEW_LINES)
);

$monkeys = array_combine(array_column($input, 'label'), $input);
$rootMonkey = $monkeys['root'];
function buildTree(array $monkey, array &$monkeys): Node
{

    if (isset($monkey['value'])) {
        return Node::Leaf($monkey['label'], $monkey['value']);
    }
    $left = buildTree($monkeys[$monkey['left']], $monkeys, 'left');
    $right = buildTree($monkeys[$monkey['right']], $monkeys, 'right');
    return Node::InternalNode($monkey['label'], $monkey['op'], $left, $right);
}

$tree = buildTree($rootMonkey, $monkeys);
$tree->v = '=';

function getItinerary(Node|null $n, string $objective): array|null
{
    if ($n === null) {
        return null;
    } elseif ($n->name === $objective) {
        $n->v = 'x';
        return [$n];
    }

    $it = getItinerary($n->l, $objective);
    if (is_array($it)) {
        $it[] = $n;
        return $it;
    }

    $it = getItinerary($n->r, $objective);
    if (is_array($it)) {
        $it[] = $n;
        return $it;
    }

    return null;
}

$it = getItinerary($tree, 'humn');

function getShout(Node $root): int
{
    return match($root->v) {
        '+' => getShout($root->l) + getShout($root->r),
        '-' => getShout($root->l) - getShout($root->r),
        '*' => getShout($root->l) * getShout($root->r),
        '/' => intdiv(getShout($root->l), getShout($root->r)),
        default => $root->v,
    };
}

function getShoutString(Node $root): string
{
    if ($root->name === 'humn') {
        return 'x';
    }
    
    return match($root->v) {
        '+','-','*','/' =>  $root->v . " " . getShoutString($root->l) . " " . getShoutString ($root->r),
        default => strval($root->v)
    };
}

array_walk($it, function (Node $n) {echo "$n->name: $n->v\n";});

$solver = function (Node $root, array $it) use (&$solver): int
{
    if ($root->l->name === 'humn') {
        return $root->r->v;
    }
    if ($root->r->name === 'humn') {
        return $root->l->v;
    }

    $n = array_pop($it);
    if ($n === $root) {
        return $solver ($root, $it);
    }
    
    [$oppositeBranch, $itineraryBranch] = ($n->l->name === $it[array_key_last($it)]->name) ? [$n->r, $n->l] : [$n->l, $n->r];
    if ($root->l === $n) {
        if ($n->v === '+') {
            if (is_int($root->r)) {
                $scalar = Node::Leaf('SCALAR', $root->r - getShout($oppositeBranch));
            } else {
                $scalar = Node::Leaf('SCALAR', getShout($root->r) - getShout($oppositeBranch));
            }
        } elseif ($n->v === '-') {
            if ($oppositeBranch->name === $n->r->name) {
                if (is_int($root->r)) {
                    $scalar = Node::Leaf('SCALAR', $root->r + getShout($oppositeBranch));
                } else {
                    $scalar = Node::Leaf('SCALAR', getShout($root->r) + getShout($oppositeBranch));
                }
            } else { // $oppositeBranch->name === $n->l->name
                if (is_int($root->r)) {
                    $scalar = Node::Leaf('SCALAR', getShout($oppositeBranch) - $root->r);
                } else {
                    $scalar = Node::Leaf('SCALAR', getShout($oppositeBranch) - getShout($root->r));
                }
            }
        } elseif ($n->v === '*') {
            if (is_int($root->r)) {
                $scalar = Node::Leaf('SCALAR', intdiv($root->r,getShout($n->r)));
            } else {
                $scalar = Node::Leaf('SCALAR', intdiv(getShout($root->r),getShout($oppositeBranch)));
            }
        } elseif ($n->v === '/') {
            if ($oppositeBranch->name === $n->r->name) {
                if (is_int($root->r)) {
                    $scalar = Node::Leaf('SCALAR', $root->r * getShout($n->r));
                } else {
                    $scalar = Node::Leaf('SCALAR', getShout($root->r) * getShout($oppositeBranch));
                }
            } else { // $oppositeBranch->name === $n->l->name
                if (is_int($root->r)) {
                    $scalar = Node::Leaf('SCALAR', intdiv(getShout($n->r), $root->r));
                } else {
                    $scalar = Node::Leaf('SCALAR', intdiv(getShout($oppositeBranch), getShout($root->r)));
                }
            }
        }
        $root->r = $scalar;
        $root->l = $itineraryBranch;
        return $solver($root, $it);
    }
    return -1;
};

$rightResult = getShout($tree->r);
$leftExpression = getShoutString($tree->l);

echo $leftExpression . PHP_EOL;
echo "= $rightResult\n";

echo "x = " . $solver($tree, $it) . PHP_EOL;
