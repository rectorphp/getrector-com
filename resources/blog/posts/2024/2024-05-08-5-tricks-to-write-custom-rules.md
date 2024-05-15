---
id: 66
title: "5 Tricks to Write Custom Rules"
perex: |
    Rector and its [extensions](https://github.com/rectorphp/rector/?tab=readme-ov-file#empowered-by-community-heart) already consist of many rules for PHP upgrades, Framework upgrades, improve code quality and type coverage, however, you may need your own needs, that's the time you need to write your own custom rules.

    Yes, there is documentation for how to [write custom rules](https://getrector.com/documentation/custom-rule), but the following tricks can help you more.
---

## 1. Decide what `Node` to be changed **before** vs **after** that is Needed

There are usually 2 kinds of `Node` instance that you can use:

- `PhpParser\Node\Expr`
- `PhpParser\Node\Stmt`

That will ensure the node and it structure always correctly refreshed after refactored, to avoid error:

```bash
Complete parent node of "PhpParser\Node\Attribute" be a stmt.
```

Except you have a very specific use case that it may be ok to not refresh it, eg:

- `PhpParser\Node\Name`
- `PhpParser\Node\Identifier`
- `PhpParser\Node\Param`
- `PhpParser\Node\Arg`
- `PhpParser\Node\Expr\Variable`

The list are in [`ScopeAnalyzer::NON_REFRESHABLE_NODES` constant](https://github.com/rectorphp/rector-src/blob/650dcc6394c6df206772350e525311f8080e5077/src/NodeAnalyzer/ScopeAnalyzer.php#L19).

To know what `Node` is needed to be changed, you can see visual [documentation of PHP Parser nodes](https://github.com/rectorphp/php-parser-nodes-docs), that you can see online at [Play with AST Page](https://getrector.com/ast) to see what target node to be used. We have a blog post for that at [Introducing with AST Page](https://getrector.com/blog/introducing-play-with-ast-page).

## 2. Return `null` on no change, return the `Node` or array of `Stmt` `Node` on changed

For example:

```php
public function refactor(Node $node): ?Node
{
    if ( /* some condition */ ) {
        return null;
    }

    if (/* some condition */) {
        // make a change to Node
        return $node;
    }

    // return array of nodes, which should be array of Stmt[],
    // eg: insert new line before existing stmt
    return [
        new \PhpParser\Node\Stmt\Nop(),
        $node,
    ];
}
```

## 3. Return `NodeTraverser::REMOVE_NODE` to remove `Stmt` node

For example, you want to remove `If_` stmt:

```diff
-if (false === true) {
-    echo 'dead code';
-}
```

You can returns `\PhpParser\NodeTraverser::REMOVE_NODE`, eg:

```php
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\NodeTraverser;
use PhpParser\Node\Stmt\If_;

/**
 * @param If_ $node
 */
public function refactor(Node $node): ?int
{
    if ($node->cond instanceof Identical) {
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

so the `If_` node will be removed.

## 4. Return `NodeTraverser::DONT_TRAVERSE_CURRENT_AND_CHILDREN` to skip `Node` below target `Node` on current `Rector` Rule

For example, you need to check `Array_` node, but don't want to check if the `Array_` is inside `Property` or `ClassConst` `Node`, you can returns `\PhpParser\NodeTraverser::DONT_TRAVERSE_CURRENT_AND_CHILDREN`, which the `AbstractRector` cover it to ensure it remembered to [only skip below target `Node` on current `Rector` rule](https://github.com/rectorphp/rector-src/blob/6bd2b871c4e9741928fb48df3ca8e899be42be81/src/Rector/AbstractRector.php#L269-L291).

so, you have the following target node types:

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

then, you can check:

```php
use PhpParser\NodeTraverser;

// ...
/**
 * @param Property|ClassConst|Array_ $node
 */
public function refactor(Node $node): ?int
{
    if ($node instanceof Property || $node instanceof ClassConst) {
        return NodeTraverser::DONT_TRAVERSE_CURRENT_AND_CHILDREN;
    }

    // process Array_ node
}
```

so, it will not chek:

- below current `Node`
- on current Rector rule only.

otherwise, it will be processsed.

<br>

Happy coding!
