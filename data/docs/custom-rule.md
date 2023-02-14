First, make sure it's not covered by [any existing Rectors](https://github.com/rectorphp/rector/blob/main/docs/rector_rules_overview.md).
Let's say we want to **change method calls from `set*` to `change*`**.

```diff
 $user = new User();
-$user->setPassword('123456');
+$user->changePassword('123456');
```

## 1. Create a New Rector and Implement Methods

Create a class that extends [`Rector\Core\Rector\AbstractRector`](https://github.com/rectorphp/rector/blob/main/src/Rector/AbstractRector.php). It will inherit useful methods e.g. to check node type and name. See the source (or type `$this->` in an IDE) for a list of available methods.

```php
declare(strict_types=1);

namespace Utils\Rector\Rector;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Expr\MethodCall;
use Rector\Core\Rector\AbstractRector;
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
        // pick any node from https://github.com/rectorphp/php-parser-nodes-docs/
        return [MethodCall::class];
    }

    /**
     * @param MethodCall $node - we can add "MethodCall" type here, because
     *                         only this node is in "getNodeTypes()"
     */
    public function refactor(Node $node): ?Node
    {
        // we only care about "set*" method names
        if (! $this->isName($node->name, 'set*')) {
            // return null to skip it
            return null;
        }

        $methodCallName = $this->getName($node->name);
        $newMethodCallName = preg_replace('#^set#', 'change', $methodCallName);

        $node->name = new Identifier($newMethodCallName);

        // return $node if you modified it
        return $node;
    }

    /**
     * This method helps other to understand the rule and to generate documentation.
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
/src/
    /YourCode.php
/utils
    /rector
        /src
            /Rector
                MyFirstRector.php
rector.php
composer.json
```

Writing test saves you lot of time in future debugging. Here is a file structure with tests:

```bash
/src/
    /YourCode.php
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

### Writing tests

Rector provides a structured way of running your rules on snippets of code so you can validate that your rule works as expected.
It uses PHPUnit to run the tests and `rector/rector` provides the `AbstractRectorTestCase` class to simplify test configuration.

The usual structure of the test class is as follows:
```php
declare(strict_types=1);

namespace Utils\Rector\Tests\Rector\MyFirstRector;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class MyFirstRectorTest extends AbstractRectorTestCase
{
    #[DataProvider('provideData')]
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    public static function provideData(): Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
```

You can see that there are 3 functions in this test class:

- `public function test(string $filePath): void`:
  - This method is to help PHPUnit detect this test
  - For `$filePath`, we use a [PHPUnit DataProvider](https://phpunit.readthedocs.io/en/10.0/writing-tests-for-phpunit.html#data-providers)
  - This triggers a run for every test file in your Fixtures directory
- `public static function provideData(): Iterator`:
  - As stated above, this is a PHPUnit DataProvider
  - Using `self::yieldFilesFromDirectory` it iterates over all test cases you provided
  - See `How To Add Test Case` for the files that are expected in the `/Fixture` directory
- `public function provideConfigFilePath(): Iterator`:
  - This should return a `rector.php`-styled file configuring the minimal set of rules needed to run the tests (including `MyFirstRectorRule`)

You can run your tests with `vendor/bin/phpunit utils/rector/tests`

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

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(MyFirstRector::class);
};
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
