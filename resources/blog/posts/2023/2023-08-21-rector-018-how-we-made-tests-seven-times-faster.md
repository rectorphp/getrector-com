---
id: 52
title: "Rector 0.18 - How we made tests Seven Times Faster"
perex: |
   The developer experience is a priority when it comes to contributing tools, fixing bugs, and delivering merge requests fast. Rector 0.17 tests could **eat up enough memory to crash on 16 GB RAM and took 3-5 minutes to complete**.

    This was painful and lead developers to skip test run locally and wait for the CI.

    We wanted [fast feedback](https://tomasvotruba.com/blog/2020/01/13/why-is-first-instant-feedback-crucial-to-developers), so **everyone can enjoy fast feedback**. We worked hard past 2 months to make our tests faster than a sip of a good coffee.
---

## Why so Slow?

At first, we had to identify, *why* are our tests so slow. Let's take it step by step. The code is being parsed by php-parser; we have around 3 300 tests. That means:

* php-parser parses test fixture to nodes, typically PHP class of 15 lines,
* then PHPStan decorates nodes with types,
* then Rector uses registered rules and changes nodes,
* then the printer prints nodes back to the string,
* finally, the printed string is compared to an expected one.

<br>

**This happened 3 300 times**. We tried to speed up the process by removing unnecessary iterations, which helped on a single-project run. We're very grateful for [the tremendous work Marcus Staab](https://staabm.github.io/2023/05/06/racing-rector.html) has done in this area.

<br>

So we were sure the **bottleneck was elsewhere**. But where?

<img src="https://user-images.githubusercontent.com/924196/261989353-72ff0dfe-e881-4174-9bf0-d38d72e619e8.png" class="img-thumbnail">

<em>Tests in Rector 0.17 take **73 seconds** and **8.17 GB of memory**.</em>

<br>

The idea came from a lucky experiment. A few weeks ago, I flipped [ECS from Symfony to Laravel container](https://tomasvotruba.com/blog/experiment-how-i-replaced-symfony-di-with-laravel-container-in-ecs) because it's much easier to use when it comes to CLI apps - we downgrade whole /vendor to PHP 7.2 and prefix every single class.

Surprisingly, the container switch affected test speed as well. Tests went [from 0,75 s down to 0,17 s](https://twitter.com/VotrubaT/status/1683576139049058304) - that's 77 % faster. These numbers are too small to take seriously, but it gave us a hint - maybe we **could achieve a similar speed-up in Rector**.

Even going down from 80 seconds to 30 would make contributing Rector more joyful.

<br>

The speed 7x speed up is **combination of 3 changes**.

<br>

## 1. From Compiled contains to Lazy Container

In every test, the container has to load config, register rules as a service, set up parameters and then invoke the cycle above. The Symfony container is compiled, so the fluent PHP config that `RectorConfig` is dumped to a cached PHP file. This brings excellent performance on HTTP requests per second but can put a massive burden on your local on 3300 different tests. Symfony parameter bag is tightly coupled to the container,  so to invalidate, e.g., paths or skip parameters, the container cache rebuild is needed.

<br>

On the other hand, the Laravel container is *lazy* - it only creates the services you need when you need them. The Rector core contains ~ 400 services. If you need to test a single service, the Laravel container will create a single service with its dependency tree.

<br>

We switched **the container from compiled and cached to lazy one**.

* Rector tests now run faster, as typical test runs a single Rector service with the same dependencies
* We create a single shared container for all the 3300 tests.
* This means if a service is injected in each of those tests, it will be created just once and reused.

But that wasn't enough.

<br>

## 2. Identify Resettable Services

When we moved from compiled container per test case to a lazy one, we had another problem. Some services kept state and piled up configuration or cached values. E.g., a collector that kept class renames was growing on every run.

We had to identify these services and mark them with `ResetableInterface` to set their state empty:

```php
final class RenamedClassesDataCollector implements ResetableInterface
{
    /**
     * @var array<string, string>
     */
    private array $oldToNewClasses = [];

    public function reset(): void
    {
        $this->oldToNewClasses = [];
    }

    // ...
}
```

And reset them on every test run:

```php
protected function setUp()
{
    // ...
    $renamedClassesDataCollector = $container->make(RenamedClassesDataCollector::class);
    $renamedClassesDataCollector->reset();
}
```

Instead of container-coupled parameter services like in a compiled container, we used a static parameter provider that we reset in `tearDown()`. This allowed us to keep the lazy container and parameter configuration separate.

<br>

## 3. Avoid new Nodes in Data Providers

Last but not least, we improved speed on bizarre time leaks.

There were 2 test cases related to the doc block parsing/printer on multiline docblocks, like Doctrine many to many.

They were specific by interesting behavior - when we ran them standalone, it took around 80 ms. But the more tests were run before them, the longer it took. E.g., the whole test suite took 2-3 minutes more.

It was not in Rector code, PHPStan code, Laravel container, or any other used dependency. Well, it ones one of the dependencies - the PHPUnit.

The PHPUnit 10 made a change in data providers to require them to be `static`. It's probably related to some caching because in this code, it spiked a 2-3 minute delay:

```php
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node;

#[DataProvider('provideData')]
public function test(string $filePath, Node $node)
{
    // ...
}

public static function provideData(): Iterator
{
    yield [
        __DIR__ . '/some_file.txt',
        new Class_(IndexInTable::class),
    ];
}
```

So instead of 10 seconds, the whole **test suite would run a couple of minutes** even on the Laravel container. It has probably something to do with the [`jsonSerialize()` method of `PhpParser\NodeAbstract`](https://github.com/nikic/PHP-Parser/blob/a6303e50c90c355c7eeee2c4a8b27fe8dc8fef1d/lib/PhpParser/NodeAbstract.php#L172-L177) and PHPUnit caching.

Other value objects like PHPStan type objects can be used in data providers with no performance hit.

<br>

So, **how we fixed it**? Simply using nodes outside the data provider:

```diff
 use PhpParser\Node\Stmt\Class_;
 use PhpParser\Node;

 #[DataProvider('provideData')]
-public function test(string $filePath, Node $node)
+public function test(string $filePath, string $className)
 {
+    $node = new Class_($className);
     // ...
 }

 public static function provideData(): Iterator
 {
     yield [
         __DIR__ . '/some_file.txt',
-        new Class_(IndexInTable::class),
+        IndexInTable::class,
     ];
 }
```

This removed our last and slowest annoying bottleneck.


<br>

## And the results?

<img src="https://user-images.githubusercontent.com/924196/261989437-70f02020-c12b-4e7a-8341-aed3f98bc41a.png" class="img-thumbnail">

<br>
<br>

* From 73 seconds to **10 seconds**

* From 8 170 MB of memory to **633 MB**

* We narrowed 8 parallel test jobs **[to single one](https://github.com/rectorphp/rector-src/pull/4827)** with 20 seconds run.

<br>

Job well done!

We know there is still space to improve the container. Do you have some experience with Laravel container performance optimization? Please, roast our [container factory](https://github.com/rectorphp/rector-src/blob/main/src/DependencyInjection/LazyContainerFactory.php).

We want to make it even faster, so any PHP developer can run Rector on any machine worldwide. Thank you!

<br>

Happy coding!
