---
id: 49
title: "Rector 0.17 brings More Robust and Lighter Node Tree"
perex: |
    Rector has matured enough to start thinking about a stable version. This year, we want to release Rector 1.0. Before that happens, we want to ensure it is available to variety of users and the known splinters are removed.

    One of them is **to lower memory consumption** - so Rector runs **faster on any laptop anywhere in the world**.

since_rector: 0.17.0
---

To achieve this, we are removing the next, previous, and parent nodes connection in the node tree:

```php
public function refactor(Node $node)
{
    // is null in Rector 0.17
    $parentNode = $node->getAttribute(AttributeKey::PARENT_NODE);
}
```

This will make the node tree more robust, as each node should know about only its child nodes, not about the whole tree. It's like dependency injection, where the service only knows about its dependencies, not the whole container.

<img src="https://github.com/rectorphp/getrector-com/assets/924196/a62176bb-a217-4d89-88f9-4b77aaac6b7d" class="img-thumbnail mb-4 mt-4">


## Before and After

PHPStan has done [the same move in April 2022](https://phpstan.org/blog/preprocessing-ast-for-custom-rules), and Rector architecture now follows the same path. We borrow the following example from their blog post to show the change on a `try/catch` node.

Before this change, the node tree looked like this - every node knows about every other node:

<img src="https://phpstan.org/mermaid-6d0ce15eb0039ff974c3884feda067b5eec0efe304ee41e31933d7bf3ac748a0.a0ba9b00.svg" style="width: 36em">

<br>

Now, it looks simple like this:

<img src="https://phpstan.org/mermaid-46470766f874d0ff9817a88d3b95b15dfaed05f2782e7cb6db9ff8e2cf7879fb.cf58c91d.svg" style="width: 19em">

<br>
<br>

With such a simpler architecture, the rules are faster because the node tree doesn't have to remember that many references. But also, **rules are easier to read as we go from top to bottom**.

<br>

To land this change in the Rector codebase, [we've done over 100 pull requests](https://github.com/rectorphp/rector/issues/7947) to core and extensions:

<img src="https://github.com/rectorphp/getrector-com/assets/924196/0a13ed20-a6ed-4b3f-bcc7-c9c12714672b" class="img-thumbnail" />

<br>


## Rule of the Thumb - Use First Relevant Node

How do you **upgrade your custom rules**? Check [the issue](https://github.com/rectorphp/rector/issues/7947) for examples of refactoring.

During refactoring, we noticed some rules were scratching a left ear with its right hand. E.g., hooking to the `Property` node to add a method on a parent `Class_`. Instead, the rule should hook into the lowest node possible and the highest relevant node being changed.

In the example, it should hook into the `Class_` node, find a property in the class and add a method there. This way, the rule will be faster and more robust:

```php
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\Rector\AbstractRector;

final class AddMethodBasedonPropertyRector extends AbstactRector
{
    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        $addMethod = false;

        foreach ($node->getProperties() as $property) {
            // detect relevant property
            if (...) {
                $addMethod = true;
            }
        }

        if ($addMethod === false) {
            return null;
        }

        // add class method here
        $node->stmts[] = new ClassMethod(...);

        return $node;
    }
}
```

<br>

Update your rules to use the first relevant node, and you'll be ready for Rector 1.0.

<br>

Happy coding!
