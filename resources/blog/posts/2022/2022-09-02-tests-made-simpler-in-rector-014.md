---
id: 41
title: "Tests Made Simpler in Rector 0.14"
perex: |
    In August, we've been working hard to make Rector lighter. As use the count of users grows, developers use Rector on more legacy projects than before, and user experience b becomes a higher priority. The easy use, installation, and writing of custom rules is the key to the success of any project upgrade.

    We cut down dependencies that it really does not need, removed a few niche features, and made the test case simpler.

    You can benefit from this change if you're using Rector to write your custom rules and test those. What has changed and how?
---

The Rector rule test case has 3 essential methods:

* test method that PHPUnit invokes
* data provider that provides test fixtures
* and method that provides a path to a config file

```php
use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;

final class RenameClassRectorTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(SmartFileInfo $fileInfo): void
    {
       $this->doTestFileInfo($fileInfo);
    }

    /**
     * @return Iterator<SmartFileInfo>
     */
    public function provideData(): Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
```

The main concerns were `SmartFileInfo` wrapping every test fixture, and this class includes a version of Symfony FileSystem to work with relative paths. After internal analysis, we've found that the extra methods are used in about 15 cases. We made a [pull-requests](https://github.com/rectorphp/rector-src/pull/2876) to address this issue and saw about 10 % memory less consumption narrow test suite.

## From Value Object to File Path

Wrapping a string in a value object and then creating a service in every single value object is not cheap. But further we need to ask the critical question: **"do we really need it"?**

No.

<br>

We dropped this wrapper, removed the `SmartFileInfo` wrapping from everywhere, and lowered the memory consumption of the files.

In the next Rector 0.14.1, we've also simplified the test to use direct file paths:

```php
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class RenameClassRectorTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(string $filePath): void
    {
       $this->doTestFile($filePath);
    }

    public function provideData(): Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
```

## How to Upgrade?

The upgrade is effortless. Just use PHPStorm to find the `test()` method like this:

```diff
-public function test(SmartFileInfo $fileInfo): void
+public function test(string $filePath): void
 {
-    $this->doTestFileInfo($fileInfo);
+    $this->doTestFile($filePath);
 }
```

And you're ready to go with less memory and simpler code.

<br>


Happy coding!
