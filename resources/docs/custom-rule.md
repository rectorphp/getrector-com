First, make sure it's not covered by [any existing Rectors](https://github.com/rectorphp/rector/blob/main/docs/rector_rules_overview.md).
Let's say we want to **change method calls from `set*` to `change*`**.

```diff
 $user = new User();
-$user->setPassword('123456');
+$user->changePassword('123456');
```

<br>

<div class="alert alert-warning pb-0 ps-4 pe-4">
<h1 class="float-start pe-2"> 💡</h1>

<p style="margin-top: 0.7em" class="pb-3">
Since <strong>Rector 0.19.3</strong> you can generate basic structure of your custom rule with this command:
</p>

```bash
vendor/bin/rector custom-rule
```
</div>


## 1. Create a New Rector and Implement Methods

Create a class that extends [`Rector\Rector\AbstractRector`](https://github.com/rectorphp/rector/blob/main/src/Rector/AbstractRector.php). It will inherit useful methods e.g. to check node type and name. See the source (or type `$this->` in an IDE) for a list of available methods.

```php
namespace Utils\Rector\Rector;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Expr\MethodCall;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class MyFirstRector extends AbstractRector
{
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        // what node types are we looking for?
        // pick from
        // https://github.com/rectorphp/php-parser-nodes-docs/
        return [MethodCall::class];
    }

    /**
     * @param MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        $methodCallName = $this->getName($node->name);
        if ($methodCallName === null) {
            return null;
        }

        // we only care about "set*" method names
        if (! str_starts_with($methodCallName, 'set')) {
            // return null to skip it
            return null;
        }

        $newMethodCallName = preg_replace(
            '#^set#', 'change', $methodCallName
        );

        $node->name = new Identifier($newMethodCallName);

        // return $node if you modified it
        return $node;
    }

    /**
     * This method helps other to understand the rule
     * and to generate documentation.
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Change method calls from set* to change*.', [
                new CodeSample(
                    // code before
                    '$user->setPassword("123456");',
                    // code after
                    '$user->changePassword("123456");'
                ),
            ]
        );
    }
}
```


## File Structure

This is how the file structure for custom rule in your own project will look like:

```bash
/src
    SomeCode.php

/utils
    /rector
        /src
            /Rector
                MyFirstRector.php

        /tests
            /Rector
                /MyFirstRector
                    /Fixture
                        test_fixture.php.inc
                    /config
                        config.php
                    MyFirstRectorTest.php

rector.php
composer.json
```

## Update `composer.json`

We also need to load Rector rules in `composer.json`:

```json
{
    "autoload": {
        "psr-4": {
            "App\\": "src"
        }
    },
    "autoload-dev": {
        "psr-4": {
            "Utils\\Rector\\": "utils/rector/src",
            "Utils\\Rector\\Tests\\": "utils/rector/tests"
        }
    }
}
```

After adding this to `composer.json`, be sure to reload Composer's class map:

```bash
composer dump-autoload
```

## 2. Register It

```php
use Utils\Rector\Rector\MyFirstRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([
        MyFirstRector::class
    ]);
```

## 3. Let Rector Refactor Your Code

The `rector.php` configuration is loaded by default, so we can skip it.

```bash
# see the diff first
vendor/bin/rector process src --dry-run

# if it's ok, apply
vendor/bin/rector process src
```

That's it!
