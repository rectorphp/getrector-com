---
id: 46
title: "How to Upgrade to PHPUnit 10 in Diffs"
perex: |
    PHPUnit 10 [was released today](https://phpunit.de/announcements/phpunit-10.html). Do you fancy an early upgrade?

    We'll show you how to do it with Rector and what other changes you have to handle. Ready?

since_rector: 0.15.12
---

## What has changed regarding the dependencies?

* PHPUnit 10 now requires PHP 8.1+
* and it requires `Sebastian/diff` 5.0 instead of 4.0

The first is a hard requirement, so if you're stuck on PHP 8.0, do not upgrade.

<br>

The second one can be vendor locked by other packages. If the `Sebastian/diff` is a blocker for you, use the composer alias to allow it:

```diff
 {
     "require": {
-        "sebastian/diff": "^4.0"
+        "sebastian/diff": "5.0 as 4.0.4"
     }
 }
```

<br>

## Update the `Differ` class

Do you use the `sebastian/diff` in your code? The `Differ` class now requires an explicit dependency via a constructor. So add it:

```diff
 use SebastianBergmann\Diff\Differ;
 use SebastianBergmann\Diff\Output\StrictUnifiedDiffOutputBuilder;

+$unifiedDiffOutputBuilder = new UnifiedDiffOutputBuilder();
-$differ = new Differ()
+$differ = new Differ($unifiedDiffOutputBuilder);
```

<br>

## Update `.gitignore` paths

New PHPUnit 10 uses the whole directory for caching instead of a single file:

```diff
 # .gitignore
-.phpunit.result.cache
+/.phpunit.cache
```

<br>

## And the Rest?

* data providers must be static methods now
* the `@annotations` are flipped to `#[attributes]`
* the abstract "test" must have `TestCase` suffix

```diff
 use PHPUnit\Framework\TestCase;
+use PHPUnit\Framework\Attributes\DataProvider;

 final class SomeTest extends TestCase
 {
-    /**
-     * @dataProvider provideData()
-     */
+    #[DataProvider('provideData')]
     public function test(int $value)
     {
     }


-    public function provideData()
+    public static function provideData()
     {
        yield [10_0];
     }
}
```

<br>

Note: Do your data providers contain dynamic method calls? You'll need to refactor them to static ones first:

```diff
 public static function provideData()
 {
-    yield $this->loadDirectory(__DIR__ . '/Fixtures');
+    yield self::loadDirectory(__DIR__ . '/Fixtures');
 }
```


<br>

The `abstract` test now has to have `*TestCase` suffix:

```diff
 use PHPUnit\Framework\TestCase;

-abstract AbstractTypeTest extends TestCase
+abstract AbstractTypeTestCase extends TestCase
 {
 }
```

<br>

To handle these, add PHPUnit 10 upgrade set to the `rector.php` config:

```php
use Rector\Config\RectorConfig;
use Rector\PHPUnit\Set\PHPUnitSetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->sets([
        PHPUnitSetList::PHPUNIT_100,
    ]);
};
```

<br>

Then run Rector on tests to upgrade:

```bash
vendor/bin/rector tests
```

That's it! We made an [upgrade  of Rector tests](https://github.com/rectorphp/rector-src/pull/3332) based on this tutorial.

<br>

Enjoy your new PHPUnit 10 tests today!

<br>

Happy coding!
