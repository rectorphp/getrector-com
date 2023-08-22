---
id: 51
title: "Using Scoped Traverse to Locate Specific Node"
perex: |
    Rector no longer support parent lookup since version 0.17, so lookup specific node will need to resolve from parent to child instead. You can traverse with `SimpleCallableNodeTraverser`.
---

For example, on the following code:

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

You want to collect return values, but not inside inner scope (_anonymous class_, _inner function_, or _closure_), you can use `PhpParser\Node\Stmt\ClassMethod` as node types:

```php
use PhpParser\Node\Stmt\ClassMethod;

// ...
public function getNodeTypes()
{
    return [ClassMethod::class];
}
```

Then, you can do in `refactor()` method:

```php
use PhpParser\Node\Stmt\Return_;

public function refactor(Node $node): ?Node
{
    $returns = $this->betterNodeFinder->findInstancesOfInFunctionLikeScoped($node, Return_::class);

    if ($returns === []) {
        return null;
    }

    // process $returns nodes here ...
}
```

Above, the `findInstancesOfInFunctionLikeScoped` is using `SimpleCallableNodeTraverser` internally, we verify if we enter an anonymous class, or inner function, or closure inside `PhpParser\Node\Stmt\ClassMethod`'s, we don't traverse inside, so continue to other node. On current codebase, we will only get 2 returns:

```php
return 'A';
return $someClosure() + 2;
```

How about processing directly, we can directly using `SimpleCallableNodeTraverser`, for example, we need to replace `return 'A';` to `return self::LABEL_A`:

```php
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\Return_;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Name;
use PhpParser\NodeTraverser;

public function refactor(Node $node): ?Node
{
    if ($node->stmts === null) {
        return null;
    }

    $this->traverseNodesWithCallable(
        $node->getStmts(),
        static function (Node $subNode) use (&$returns): ?int {
            if ($subNode instanceof Class_
                || $subNode instanceof Function_
                || $subNode instanceof Closure) {
                return NodeTraverser::DONT_TRAVERSE_CURRENT_AND_CHILDREN;
            }

            if ($subNode instanceof Return_ && $this->valueResolver->getValue($subNode->expr) === 'A') {
                $subNode->expr = new ClassConstFetch(new Name('self'), 'LABEL_A');

                // want to stop after first found?
                return NodeTraverser::STOP_TRAVERSAL;
            }

            return null;
        });
}
```

that's it ;)

<br>

Happy coding!
