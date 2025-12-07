You can add new NodeVisitors to decorate nodes with attributes before being used by one or more rules. This is useful if you need to add metadata to nodes, that are known only in higher nodes. E.g. if `Expr` is part of assign:

```php
$value = 100;
```

First we create a node visitor, that implements `Rector\Contract\PhpParser\DecoratingNodeVisitorInterface`:

```php
<?php

declare(strict_types=1);

namespace My\Rector\Visitor;

use PhpParser\Node;
use PhpParser\Node\Stmt;
use PhpParser\Node\Expr\Assign;
use PhpParser\NodeVisitorAbstract;
use Rector\Contract\PhpParser\DecoratingNodeVisitorInterface;

final class AssignAwareNodeVisitor extends NodeVisitorAbstract implements DecoratingNodeVisitorInterface
{
    public const IS_PART_OF_ASSIGN = 'is_part_of_assign';

    public function enterNode(Node $node)
    {
        if (! $node instanceof Assign) {
            return null;
        }

        $node->var->setAttribute(self::IS_PART_OF_ASSIGN, true);
        $node->expr->setAttribute(self::IS_PART_OF_ASSIGN, true);

        return null;
    }
}
```

Never modify any nodes in the visitor as it might break node tree traversal. Only add attributes to nodes.

<br>

### Register in Rector

Then, register in the `rector.php` config:

```php
<?php

use Rector\Config\RectorConfig;
use My\Rector\Visitor\HelloVisitor;

return RectorConfig::configure()
    ->registerDecoratingNodeVisitor(ScopeResolverNodeVisitorInterface::class);
```

<br>

If you're adding similar node visitor to Rector codebase, add class to  `Rector\DependencyInjection\LazyContainerFactory::DECORATING_NODE_VISITOR_CLASSES` to register it.

<br>

Then, you can check the attribute in any Rector rule:

```php
fianl class SomeRector extends AbstractRector
{
    public function refactor(Node $node): ?Node
    {
        if ($node->getAttribute(AssignAwareNodeVisitor::IS_PART_OF_ASSIGN)) {
            // we know node is part of assign
        }

        // ...

        return null;
    }
}
```

<br>

### Use Existing Attribute Keys

Rector provides couple such node visitors out of the box. You can use these attributes already.

To find out list of attributes you can use, see `IS_*` constants in
[`AttributeKey` class](https://github.com/rectorphp/rector-src/blob/main/src/NodeTypeResolver/Node/AttributeKey.php).
