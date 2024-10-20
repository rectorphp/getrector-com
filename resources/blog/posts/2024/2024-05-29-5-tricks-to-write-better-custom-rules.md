---
id: 67
title: "5 Tricks to Write Better Custom Rules"
perex: |
    Rector and its [extensions](https://github.com/rectorphp/rector/?tab=readme-ov-file#empowered-by-community-heart) already consist of many rules for PHP and Framework upgrades, improving code quality and type coverage. However, you may have your own needs - that's when you need to write your own custom rules.

    There is documentation for how to [write custom rules](https://getrector.com/documentation/custom-rule), but the following tricks can help you more.

author: samsonasik
---

## 1. Decide What `Node` to be Changed **before** vs **after** that is Needed

There are usually 2 kinds of `Node` instances that you can use:

- `PhpParser\Node\Expr`
- `PhpParser\Node\Stmt`

That will ensure the node and its structure are always correctly refreshed after refactored to avoid errors:

```bash
Complete parent node of "PhpParser\Node\Attribute" be a stmt.
```

<br>

In some cases, it may be ok not to refresh the node, e.g.:

- `PhpParser\Node\Name`
- `PhpParser\Node\Identifier`
- `PhpParser\Node\Param`
- `PhpParser\Node\Arg`
- `PhpParser\Node\Expr\Variable`

The list is in [`ScopeAnalyzer::NON_REFRESHABLE_NODES` constant](https://github.com/rectorphp/rector-src/blob/650dcc6394c6df206772350e525311f8080e5077/src/NodeAnalyzer/ScopeAnalyzer.php#L19).

To know what `Node` we need to change, you can see the visual [documentation of PHP Parser nodes](https://github.com/rectorphp/php-parser-nodes-docs). You can also use [Play with AST Page](https://getrector.com/ast) with visual and interactive code. We have a blog post at [Introducing with AST Page](https://getrector.com/blog/introducing-play-with-ast-page).

## 2. Utilize `dump_node()` and `print_node()` for Debugging During Writing

When you're on deep `Node` checking, you can directly get the `Node` structure or printed `Node` via Node utility:

```php
dump_node($node); // show AST structure
print_node($node); // print content of Node
```

## 3. Return `null` for no change, the `Node` or array of `Stmt` on Changed

For example:

```php
public function refactor(Node $node): null|Node|array
{
    if ( /* some condition */ ) {
        return null;
    }

    if (/* some condition */) {
        // make a change to Node
        return $node;
    }

    // return array of nodes, which should be an array of Stmt[],
    //e.g., insert a new line before existing stmt
    return [
        new \PhpParser\Node\Stmt\Nop(),
        $node,
    ];
}
```

## 4. Return `NodeTraverser::REMOVE_NODE` to remove the `Stmt` node

For example, you want to remove `If_` stmt:

```diff
-if (false === true) {
-    echo 'dead code';
-}
```

<br>

You can return `\PhpParser\NodeTraverser::REMOVE_NODE`, eg:

```php
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\NodeTraverser;
use PhpParser\Node\Stmt\If_;
use Rector\PhpParser\Node\Value\ValueResolver;

public function __construct(private readonly ValueResolver $valueResolver)
{
}

public function getNodeTypes(): array
{
    return [If_::class];
}

/**
 * @param If_ $node
 */
public function refactor(Node $node): ?int
{
    if (! $node->cond instanceof Identical) {
        return null;
    }

    if (! $this->valueResolver->isFalse($node->cond->left)) {
        return null;
    }

    if (! $this->valueResolver->isTrue($node->cond->right)) {
        return null;
    }

    return NodeTraverser::REMOVE_NODE;
}
```

Then, the `If_` node will be removed.

## 5. Return `NodeTraverser::DONT_TRAVERSE_CHILDREN` to skip `Node` below target `Node`

For example, if you need to check the `Array_` node but don't want to check if the `Array_` is inside `Property` or `ClassConst` `Node`, you can return `\PhpParser\NodeTraverser::DONT_TRAVERSE_CHILDREN`.

That way, Rector [skips below target `Node` on current `Rector` rule](https://github.com/rectorphp/rector-src/blob/6bd2b871c4e9741928fb48df3ca8e899be42be81/src/Rector/AbstractRector.php#L269-L291).

so you have the following target node types:

```php
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\Node\Expr\Array_;

// ...
    public function getNodeTypes(): array
    {
        return [
            Property::class,
            ClassConst::class,
            Array_::class
        ];
    }
```

<br>

Then, you can check the following:

```php
use PhpParser\NodeTraverser;

// ...
/**
 * @param Property|ClassConst|Array_ $node
 */
public function refactor(Node $node): null|int|Node
{
    if ($node instanceof Property || $node instanceof ClassConst) {
        // Array_ below Property and ClassConst won't be processed
        return NodeTraverser::DONT_TRAVERSE_CHILDREN;
    }

    // process Array_ node that is not below Property and ClassConst
}
```

<br>

So, it will:

* skip below the current `Node`
* on current Rector rule only

Otherwise, it will be processed.

<br>

We hope these tips will give you confidence to experiment with more advanced rules and save even more time with automated work.


<br>

Happy coding!
