You can add new NodeVisitors to process nodes in the `rector.php` config, make sure you tag it correctly so dependency injection knows where to look:

```php
use Rector\Config\RectorConfig;
use My\Rector\Visitor\HelloVisitor;
use Rector\NodeTypeResolver\PHPStan\Scope\Contract\NodeVisitor\ScopeResolverNodeVisitorInterface; 


return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->services()->set(HelloVisitor::class)->tag(ScopeResolverNodeVisitorInterface::class);
};
```

## A simple node visitor

```php
<?php

declare(strict_types=1);

namespace My\Rector\Visitor;

use PhpParser\Node;
use PhpParser\Node\Stmt;
use PhpParser\NodeVisitorAbstract;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\PHPStan\Scope\Contract\NodeVisitor\ScopeResolverNodeVisitorInterface;

class HelloVisitor extends NodeVisitorAbstract implements ScopeResolverNodeVisitorInterface {

    public const HELLO_ATTRIBUTE = 'rector_comment';

    public function enterNode(Node $node)
    {
        if (! $node instanceof Stmt) {
            return null;
        }

        $node->setAttribute(HelloVisitor::HELLO_ATTRIBUTE, 'i was here'));

        return $node;
    }

}
```
