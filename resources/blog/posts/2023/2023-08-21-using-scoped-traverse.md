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
    public function run()
    {
        $someClosure = function () {
            return 1;
        };

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
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Function_;
use PhpParser\NodeTraverser;

public function refactor(Node $node): ?Node
{
    if ($node->stmts === null) {
        return null;
    }

    // initialize collected returns
    $returns = [];
    $this->traverseNodesWithCallable(
        $node->getStmts(),
        static function (Node $subNode) use (&$returns): ?int {
            if ($subNode instanceof Class_
                || $subNode instanceof Function_
                || $subNode instanceof Closure) {
                return NodeTraverser::DONT_TRAVERSE_CURRENT_AND_CHILDREN;
            }

            if ($subNode instanceof Return_) {
                $returns[] = $subNode;
            }
        });

    if ($returns === []) {
        return null;
    }

    // process $returns nodes here ...
}
```

Above, we verify if we enter an anonymous class, or inner function, or closure inside `PhpParser\Node\Stmt\ClassMethod`'s, we don't traverse inside, so continue to other node. On current codebase, we will only process single return:

```php
return $someClosure() + 2;
```

that's it ;)

<br>

Happy coding!
