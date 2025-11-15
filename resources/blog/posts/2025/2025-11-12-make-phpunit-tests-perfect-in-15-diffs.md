---
id: 81
title: "Make PHPUnit tests Perfect in 15 Diffs"
perex: |

    Rector helps you improve PHP code, upgrade it to latest PHP version, make use of modern features and faster code structures. But did you know it can make your PHPUnit tests faster and easier to read?

    New PHPUnit version have more precise and reliable asserts, but most people don't know about them. They make tests run faster and in case of failure, provide more clear error message you'll understand.

    Rector can help you with that!
---

There are two main ways to keep your PHPUnit tests up-to-date and in perfect shape without any work.

## First: Use latest PHPUnit features

A year ago we introduced [Composer-version based sets](/blog/introducing-composer-version-based-sets). This feature:

* tells Rector to read your `composer.json`,
* detect your installed PHPUnit version
* and automatically pick up the sets that handle the upgrade to new features

<br>

To enable it, just add this line to your `rector.php` config:

```php
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withComposerBased(phpunit: true);
```


<br>

Setup once and forget. That's it. No need to list sets everytime new PHPUnit version is out. Rector now automatically applies PHPUnit upgrade sets, everytime you change version in `composer.json`:

```diff
 {
     "require-dev": {
-        "phpunit/phpunit": "^12.5"
+        "phpunit/phpunit": "^13.0"
     }
}
```

<br>

Run composer to install the new PHPUnit version. Then Rector to apply the upgrade:

```bash
composer update
vendor/bin/rector
```

<br>

That's it!


<br>

## Second: Use the best PHPUnit asserts and practices

Using new feature is first step to perfection, but there is more. Less code is better, and more precise assertion is better. But PHPUnit has so many different asserts, it's hard to keep up with the best ones to use in that particular situation.

<br>

### 1. Exact Assertions

In prehistorical past, there was only `assertTrue()` and `assertFalse()`. Now, PHPUnit has many more precise assertions you can use:

```diff
-$this->assertTrue(isset($anything["foo"]), "message");
+$this->assertArrayHasKey("foo", $anything, "message");
```

<br>

```diff
  public function test()
  {
      $result = '...';
-     $this->assertSame($result, 'expected');
+     $this->assertSame('expected', $result);
 }
```

<br>

```diff
-$this->assertSame(true, $value);
+$this->assertTrue($value);
```

<br>

### 2. More human-readable mocks


```diff
- ->willReturnCallback(function (...$parameters) use ($matcher) {
-     match ($matcher->getInvocationCount()) {
-         1 => $this->assertSame([1], $parameters),
-     };
- });

+ ->with(1, $parameters);
```

<br>

```diff
 $translator = $this->createMock('SomeClass');
 $translator->expects($this->any())
     ->method('trans')
-    ->will($this->returnValue('translated max {{ max }}!'));
+    ->willReturnValue('translated max {{ max }}!');
```

<br>

```diff
  $this->createMock('SomeClass')
      ->method('someMethod')
-     ->with($this->callback(function (array $args): bool {
-         return true;
-     }))
-     ->willReturn(['some item']);
+     ->willReturnCallback(function (array $args): array {
+         return ['some item'];
+     });
 ```

<br>

Why mocks property for a whole tests, if it's used only once?

```diff
 use PHPUnit\Framework\TestCase;

 class SomeServiceTest extends TestCase
 {
-    private $someServiceMock;
-
     public function setUp(): void
     {
-        $this->someServiceMock = $this->createMock(SomeService::class);
+        $someServiceMock = $this->createMock(SomeService::class);
     }
 }
```

<br>

### 3. Correct Type Declarations

```diff
 use PHPUnit\Framework\TestCase;

 class SomeTest extends TestCase
 {
     public function test()
     {
         $someClass = new SomeClass();
-        $someClass->setPhone(12345);
+        $someClass->setPhone('12345');
     }
 }

 final class SomeClass
 {
     public function setPhone(string $phone)
     {
     }
 }
```

<br>


```diff
 use PHPUnit\Framework\TestCase;

 final class SomeTest extends TestCase
 {
     public function test(): \stdClass
     {
         return new \stdClass();
     }

     /**
      * @depends test
      */
-    public function testAnother($someObject)
+    public function testAnother(\stdClass $someObject)
     {
     }
 }
```

<br>

There is no better way to start using strict types, than in tests. The safest way to spot, which places use loose types:

```diff
+declare(strict_types=1);
+
 use PHPUnit\Framework\TestCase;

 final class SomeTestWithoutStrict extends TestCase
 {
     public function test()
     {
     }
 }
```

<br>

### 4. No more call on `null` errors

Sometimes a method can return `null`, and we hope it will not. PHPStan spots these "call on possible null" cases, so Rector can help us fix them:

```diff
 use PHPUnit\Framework\TestCase;

 final class SomeTest extends TestCase
 {
     public function test()
     {
         $someObject = $this->getSomeObject();
+        $this->assertInstanceOf(SomeClass::class, $someObject);

         $value = $someObject->getSomeMethod();
     }

     private function getSomeObject(): ?SomeClass
     {
         // ...
     }
 }
```

<br>

### 5. More Readable Data Providers

Nobody likes arrays, except legacy projects who have no choice. What's even worse are nested arrays. Array in array in array. That's how data provide methods looks like. But they don't have to!

You can use `yield` with each case on standalone line - and include data just that particular line:

```diff
 use PHPUnit\Framework\TestCase;

 final class SomeTest implements TestCase
 {
     public static function provideData()
     {
-        $value = 'last text, but defined here';
-
-        return [
-            ['some text'],
-            ['another text'],
-            ['third text'],
-            [$value],
-        ];

+        yield ['some text'];
+        yield ['another text'];
+        yield ['third text'];

+        $value = 'last text, but defined here';
+        yield [$value];
     }
 }
 ```

<br>

If you still fancy `array` for data providers, make sure they're neatly indented:

```diff
-        return [['content', 8], ['content123', 11]];
+        return [
+            ['content', 8],
+            ['content123', 11]
+        ];
```

<br>

From `@testWith` to data provider method:

```diff
-/**
- * @testWith    [0, 0, 0]
- * @testWith    [0, 1, 1]
- * @testWith    [1, 0, 1]
- * @testWith    [1, 1, 3]
- */
+#[DataProvider('dataProviderSum')]
 public function testSum(int $a, int $b, int $expected)
 {
     $this->assertSame($expected, $a + $b);
 }

+public static function dataProviderSum(): array
+{
+    return [
+        [0, 0, 0],
+        [0, 1, 1],
+        [1, 0, 1],
+        [1, 1, 3]
+    ];
+}
```

<br>

...and much more. These are all part of *PHPUnit Code Quality set* with nearly 50 rules that work for you.

<br>

To enable it, just add this line to your `rector.php` config:

```php
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPreparedSets(phpunitCodeQuality: true);
```

<br>

That's it! If some rule doesn't fit your coding style, you can always [skip it](https://getrector.com/documentation/ignoring-rules-or-paths).

<br>

Happy coding!
