---
id: 83
title: "Upgrade to PHPUnit 12.5 in 7 Diffs"
perex: |
    PHPUnit 12 was released a year ago, but only PHPUnit 12.5 released in December 2025 includes valuable features that are worth it.

    The most important change, that will affect your code, is that mocks are now much stricter. There are also stubs, a mock that does nothing. How do you spot them and separate them?

    Curious how to get from 4000 notices to under 100 in 7 diffs? Read on.

since_rector: 2.3.6
---

What is the difference between a mock and a stub? You did not have to care until PHPUnit 12.5, but now you do.

Why? Because PHPUnit now complains about their misuse very verbosely. There is no way to ignore it:

<img src="/assets/images/blog/2026/phpunit-notices-spam.png" class="img-thumbnail" style="max-width: 20em">

<br>
<br>

There is more precise definition in the PHPUnit docs, but in plain English:

<blockquote class="blockquote">
"What is a difference between a mock and a stub?"
</blockquote>

<br>

* **A mock** is a fake class that has expectations about being called or not being called,

```php
$someMock = $this->createMock(SomeClass::class);
$someMock->expects($this->once())
    ->method('someMethod')
    ->willReturn(100);
```

Here we expect the `someMethod` to be called. PHPUnit will crash with error otherwise.

<br>

* **A stub** is also a fake class, but it does not do anything at all.

We can use it to make comply with constructor requirements:

```php
$someClass = new SomeClass($this->createStub(SomeDependency::class));
```

<br>

We can also use it to assert the same object is used on a getter call later:

```php
$request = $this->createStub(Request::class);
$requestStack = new RequestStack($request);

$this->assertSame($request, $requestStack->getCurrentRequest());
```

<br>

This leads us to the first and simplest change we can make.

<br>

## 1. Use `createStub()` instead of `createMock()` in arguments

The first cases are as simple as:

```diff
 $someClass = new SomeClass(
-    $this->createMock(SomeDependency::class)
+    $this->createStub(SomeDependency::class)
 );
```

<br>

Also variable assigns:

```diff
-$someDependency = $this->createMock(SomeDependency::class);
+$someDependency = $this->createStub(SomeDependency::class);

 $someClass = new SomeClass($someDependency);
```

Or coalesce directly in the argument:

```diff
-$someClass = new SomeClass(
     $someInput ?? $this->createMock(SomeDependency::class),
     $someInput ?? $this->createStub(SomeDependency::class),
 );
```

<br>

But also property fetches without any expectations:

```diff
 protected function setUp()
 {
-    $this->someDependency = $this->createMock(SomeDependency::class);
+    $this->someDependency = $this->createStub(SomeDependency::class);
 }


 public function test()
 {
     $someClass = new SomeClass($this->someDependency);
 }
```

<br>

## 2. Inline once-used Mocks Property to a Variable

This is not a change in PHPUnit 12.5 itself, but it helps with the changes that come with it. During the upgrade, I've noticed some properties are used just once.

Properties are not variables for one reason: to be used across multiple methods. Let us fix that:

```diff
-private MockObject $someDependency;

 protected function setUp()
 {
-    $this->someDependency = $this->createMock(SomeDependency::class);
 }


 public function test()
 {
-    $someClass = new SomeClass($this->someDependency);
+    $someClass = new SomeClass($this->createStub(SomeDependency::class));
 }
```

We have less code to read for us and GPT, and also can move to `createStub()` without any doubts.

<br>

<br>

## 3. Remove never used isolated mocks and dead code

Speaking of dead code, the mocks to stub narrowign also surfaces another issue: never used mocks that live on their own island.

<br>

PHPUnit being more stricter now helps us find code that was never evaluated and only taking our reading space.

```php
$this->createMock(SomeClass::class)
    ->method('someMethod')
    ->with($this->isInstanceOf(InputArgument::class))
    ->willReturn(100);
```

What is wrong with this code snippet, apart from being a stub? It is never used. We created it, but we never assigned it to a variable, nor property feath, nor argument of a method call.

It is dead code. Remove it:

```diff
-$this->createMock(SomeClass::class)
-    ->method('someMethod')
-    ->with($this->isInstanceOf(InputArgument::class))
-    ->willReturn(100);
```

<br>

Beware, this can be as complex as a well defined and typed property... that is never used. Dead code, remove it:

```diff
-private MockObject $mockProperty;

 protected function setUp(): void
 {
-     $this->mockProperty = $this->createMock(\stdClass::class);
-     $this->mockProperty->expects($this->once())
-         ->method('someMethod')
-         ->willReturn('someValue');
    }
```

<br>

## 4. From `$this->any()` to explicit expectations

PHPUnit now also deprecated used of `$this->any()` expectations. This is a wise choice, as it effectively says "we expect 0, 1, or any number of occurrences". This code as well could be removed.

<br>

Following code snippets have the same meaning:

```php
$someClass = $this->createMock(SomeClass::class);

$someClass->expects($this->any())
    ->method('someMethod')
    ->willReturn(100);

// same as
$someClass
    ->method('someMethod')
    ->willReturn(100);
```

Both will be most reported by PHPUnit as stubs. They have 0 expectations (among other numbers). So how do we fix that? Change we used before is not enough and will not work here:

```diff
-$someClass = $this->createMock(SomeClass::class);
+$someClass = $this->createStub(SomeClass::class);
```

<br>

We have to be honest here, and it might require to understand the code.

* Is it a dummy method defined in `setUp()` method, in case it will be called any further in the codebase?
* Is it implicit `$this->any()`, just because we forgot to add explicit number?

<br>

The most common case in codebases I work with was the second one:

```diff
-$someClass->expects($this->any())
+$someClass->expects($this->atLeastOnce())
     ->method('someMethod')
     ->willReturn(100);
```

But what about the `setUp()` method? Do we have to now go through all the code and inline all the properties? This hurts just writing it. This gets us to our next change:

<br>


## 5. Add `#[AllowMockObjectsWithoutExpectations]` for optional setUp mocks

It's perfectly reasonable to use `setUp()` method to create mock properties that may or may not be used in one of the test method later:

```php
private SomeObject $someDependency;

protected function setUp(): void
{
    $this->someDependency = $this->createMock(SomeDependency::class)
       // implicit ->expects($this->any())
       ->method('someMethod')
       ->willReturn(100);
}


public function testUsing()
{
    $someClass = new SomeClass($this->someDependency);
    // ...
}

public function testNotUsing()
{
    $someClass = new SomeClass(new AnotherDependnecy());
    // ...
}
```

Here we have one mocked object as a property with *any* expectations. Then there are two test methods. The first one uses the mock as a mock.
The second test method does not, so from its point of view it is a stub.

(Also, another method can be using the property, but never calling the mocked method, so it's a stub as well).

<br>

An attribute to the rescue!

```diff
 use PHPUnit\Framework\TestCase;
+use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;

+#[AllowMockObjectsWithoutExpectations]
 final class SomeTest extends TestCase
 {
     private SomeObject $someDependency;

     // ...
 }
```

This attribute will silence the notices about stubs in this test class.

<br>

We could use it on every case above, yes. But that would prevent us from obvious fixes and push the technical debt deeper under the rug with a hole under our apartment.

<br>


## 6. Cover vendor `*TestCase` classes and data providers

There are two more cases where the `#[AllowMockObjectsWithoutExpectations]` attribute is needed and makes sense.

<br>

We use a 3rd party test case class, that defines its "any" expectations for a reason. They might be used, or not. Depends on how we write the test.

```diff
 use Symfony\Component\Form\Test\TypeTestCase;

+#[AllowMockObjectsWithoutExpectations]
 final class SomeTest extends TypeTestCase
 {
     // ...
 }
```

<br>

The next is a test method that uses a data provider. The data provider usually tests edge case values that may or may not trigger a method call:

```diff
 use PHPUnit\Framework\TestCase;

+#[AllowMockObjectsWithoutExpectations]
 final class SomeTest extends TestCase
 {
     #[DataProvider('provideData')]
     public function test($input)
     {
         $someClass = $this->createMock(SomeClass::class);
         $someClass
            // implicit $this->any() here
            ->expects($this->atLeastOnce())
            ->method('someMethod')
             ->willReturn(100);

         // ...
     }

     public static function provideData(): iterable
     {
        // ...
     }
 }
```

<br>

## 7. Move from object mocking to real objects

<blockquote class="blockquote">
"The best mock is no mock at all"
</blockquote>

Before we even started the PHPUnit upgrade, we first eliminated the obvious cases that don't need any mocking at all.

We looked for plain objects, DTOs, value objects, entities and documents and replaced them with real, natively typed objects.

<br>

It can be as simple as using a simple `Request` directly:

```diff
 use PHPUnit\Framework\TestCase;
 use Symfony\Component\HttpFoundation\Request;
 use Symfony\Component\HttpFoundation\RequestStack;

 final class SomeTest extends TestCase
 {
     public function test()
     {
-        $request = $this->createMock(Request::class);
+        $request = new Request();

-        $requestStack = $this->createMock(RequestStack::class);
-        $requestStack->expects($this->atLeastOnce())
-            ->method('getMainRequest')
-            ->willReturn($request);
+        $requestStack = new RequestStack($request);

         $this->someMethod($requestStack);
    }
}
```

<br>

Simple as that. Same applies for entity/document objects. Instead of hard-to-read getter mocks, use real objects with real values and types:

```diff
-$user = $this->createMock(User::class);
+$user = new User();

-$user->expects($this->any())
-     ->method('getName')
-     ->willReturn('Tomas');
+$user->setName('Tomas');

-$user->expects($this->any())
-     ->method('getAge')
-     ->willReturn($age);
+$user->setAge($age);

 $service->process($user);
```

<br>

You can get the entity/document PHPStan spotter rule from `symplify/phpstan-rules` [here](https://github.com/symplify/phpstan-rules/blob/4b7aa41072850f9875b45272d263be3f4a183f40/src/Rules/Doctrine/NoDocumentMockingRule.php#L21).

Also, give a go to experimental [Rector rule](https://github.com/rectorphp/rector-phpunit/pull/629) that manages to change these mocks to entities. It is a real time saver.


<br>

## Enjoy the Automated Upgrade

We automated most of this work above, so you can let your agent handle the rest of the edge-cases. To get there, first enable the `phpunitCodeQuality` prepared set in your `rector.php`:

```php
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPreparedSets(phpunitCodeQuality: true)
```

And run Rector:

```bash
vendor/bin/rector
```

<br>

Only then upgrade to PHPUnit 12.5 and run Rector with composer based set:

```php
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withComposerBased(phpunit: true)
```

And run Rector again:

```bash
vendor/bin/rector
```

It will automatically pick up the PHPUnit version and apply [the 12.0 set](https://github.com/rectorphp/rector-phpunit/blob/main/config/sets/phpunit120.php) and [the 12.5 set](https://github.com/rectorphp/rector-phpunit/blob/main/config/sets/phpunit125.php).

<br>

That's all folks. I hope you enjoyed this manually written post. I certainly enjoyed writing it.

As always, if you have improvement or bug report, head to Rector on Github and let us know.

<br>

Happy coding!



