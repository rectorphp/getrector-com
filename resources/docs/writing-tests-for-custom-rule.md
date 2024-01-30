Writing test for your custom rules will save you a lot of time in future debugging.
Rector provides a structured way of running your rules on different snippets of code
so you can validate that your rule works as expected in a variety of cases.

## Requirements

There are 2 composer packages to run tests for your custom rule:

* `phpunit/phpunit`: the testing framework
* `rector/rector`: this contains the `AbstractRectorTestCase` class to simplify test configuration

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

## File Structure

Here is an example file structure for testing:

```bash
/src
    /Rector
        MyFirstRector.php
/tests
    /Rector
        /MyFirstRector
            /Fixture
                test_fixture.php.inc
                skip_rule_test_fixture.php.inc
            /config
                config.php
            MyFirstRectorTest.php
```

The files in `tests/Rector/MyFirstRector` will be explained below.

### MyFirstRectorTest.php

This class handles the heavy lifting of preparing Rector & running it against your test cases.
The usual structure of the test class is as follows:
```php
<?php

declare(strict_types=1);

namespace Package\Tests\Rector\MyFirstRector;

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
    - By default this only picks up files ending on `.php.inc`, see the `AbstractRectorTestCase` to see how you can change this.
    - See "Fixtures/*.php.inc" below for the files that are expected in the `/Fixture` directory
  - In the example file structure earlier, this would result in `test_fixture.php.inc` and `skip_rule_test_fixture.php.inc`
- `public function provideConfigFilePath(): Iterator`:
  - This should return a `rector.php`-styled file configuring the minimal set of rules needed to run the tests (including `MyFirstRectorRule`)
  - See "config/config.php" below for an example

### config/config.php

This is a `rector.php`-styled file. If your rule is not configurable, it will look like this:

```php
use Rector\Config\RectorConfig;
use Package\MyFirstRector;

return RectorConfig::configure()
    ->withRules([
        MyFirstRector::class,
    ]);
```

This essentially reflects how you would use your rule in real life.

### Fixture/*.php.inc

As mentioned in `MyFirstRectorTest.php`, these are the snippets of code on which Rector will run your custom rule.
To prevent automated tools from picking up those snippets, you need to add an extra suffix `.inc` (so `example.php` should be `example.php.inc`).

There are two options for every test file: Either the snippet should be changed by your rule or it should stay the same.

#### Fixture/test_fixture.php.inc

Assuming your rector rule changes `$user->setPassword('123456')` to `$user->changePassword('123456')`,
this is an example snippet:

```php
<?php

namespace Package\Tests\Rector\MyFirstRector\Fixture;

class SomeClass
{
    public function handlePasswordChange(User $user, string $password)
    {
        $user->setPassword($password);
    }
}

?>
-----
<?php

namespace Package\Tests\Rector\MyFirstRector\Fixture;

class SomeClass
{
    public function handlePasswordChange(User $user, string $password)
    {
        $user->changePassword($password);
    }
}

?>
```

This file contains a "before" and "after" situation, separated by exactly 5 dashes: `-----`.
The `AbstractRectorTestCase` detects the `-----`
and will run the rules configured in `config/config.php` on the snippet of code before the `-----`
and assert that the changed file exactly matches the snippet of code after the `-----`.

#### Fixture/skip_rule_test_fixture.php.inc

There are cases where you might want to check that your rule is **not** applied.
The file structure is very similar to the `Fixture/test_fixture.php.inc` with 1 exception:
It only contains a "before" situation.

It is not necessary to prefix the fixture with `skip`, but doing so makes it easy to see that no changes are expected.

Example snippet:
```php
<?php

namespace Package\Tests\Rector\MyFirstRector\Fixture;

class SomeClass
{
    public function handleLogin(User $user, string $password)
    {
        return $user->isCorrectPassword($password);
    }
}

?>
```

As you see, there is no `-----`, so `AbstractRectorTestCase` will run the rules in `config/config.php` on the snippet
and assert that there are no changes applied to the snippet.

## Running your tests

You can run your tests with `vendor/bin/phpunit tests`
