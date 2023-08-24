---
id: 54
title: "Rector 0.17 - Using Scoped Traverse to Locate Specific Node"
perex: |
    Rector [no longer support parent node lookup](/blog/rector-017-brings-more-robust-and-lighter-node-tree) since version 0.17. To lookup specific node we'll have to traverse from parent to child node instead.

    Here is how we can achieve it.
---

For example, on the following code we want to find all the `return` nodes. But we want to skip those inside **inner scope** - _anonymous classes_, _inner functions_ or _closures_.


```php
class SomeClass
{
    private const LABEL_A = 'A';

    public function run()
    {
        $someClosure = function () {
            return 1;
        };

        if (rand(0, 1)) {
            return 'A';
        }

        return $someClosure() + 2;
    }
}
```

Previously, we could hook into the `Return_` node and check if the node is located withing parent node of `PhpParser\Node\Expr\Closure;`. How can we detect the same location now?

<br>

In our rule, we hook into the top shared node - `PhpParser\Node\Stmt\ClassMethod`:

```php
use PhpParser\Node\Stmt\ClassMethod;

// ...
public function getNodeTypes()
{
    return [ClassMethod::class];
}
```

We have 2 ways to skip the closure.

<br>

## 1. Scoped Node Finder

Then, you can do in `refactor()` method:

```php
use PhpParser\Node\Stmt\Return_;

public function refactor(Node $node): ?Node
{
    $returns = $this->betterNodeFinder->findInstancesOfInFunctionLikeScoped(
        $node, Return_::class
    );

    if ($returns === []) {
        return null;
    }

    // process $returns nodes here ...
}
```

You're familiar with `findInstanceOf()` that returns all the nodes it can find of certain type.

**The `findInstancesOfInFunctionLikeScoped()` is similar, but smarter** - if it enters an anonymous class, inner function, or closure inside, it will skip it.

<br>

The example above, we'll give you only 2 items:

```php
return 'A';
return $someClosure() + 2;
```

👍

<br>

## 2. Using Node Traversing

Another option is to use `SimpleCallableNodeTraverser`. E.g. we need to replace:

```diff
-return 'A';
+return false;
```

Here is how we design the Rector rule:

```php
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\Return_;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Name;
use PhpParser\NodeTraverser;
use PhpParser\Node\Stmt\ClassMethod;

/**
 * @param ClassMethod $node
 */
public function refactor(Node $node): ?Node
{
    if ($node->stmts === null) {
        return null;
    }

    $hasChanged = false;

    $this->traverseNodesWithCallable(
        $node->stmts,
        function (Node $subNode) use (&$hasChanged): ?int {
            if ($subNode instanceof Class_
                || $subNode instanceof Function_
                || $subNode instanceof Closure
            ) {
                return NodeTraverser::DONT_TRAVERSE_CURRENT_AND_CHILDREN;
            }

            if (! $subNode instanceof Return_) {
                return null;
            }

            if (! $subNode->expr instanceof Expr) {
                return null;
            }

            if (! $this->valueResolver->isValue($subNode->expr, 'A')) {
                return null;
            }

            $subNode->expr = new ConstFetch(new Name('false'));
            $hasChanged = true;

            return null;
        }
    );

    if ($hasChanged) {
        return $node;
    }

    return null;
}
```

👍

Pick the solution that fits your situation. That's it ;)

<br>

Happy coding!
