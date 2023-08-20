---
id: 51
title: "Rector 0.18 - How we made tests Seven Times Faster"
perex: |
   The developer experience is priority when it comes to contributing tools, fixing bugs and deliver merge request fast. Rector 0.17 tests could **eat up enough memory to crash on 16 GB RAM and took 3-5 minutes to complete**.

    This was painful and let contributors and developers to skip test run locally and waiting for the CI report.

    We wanted [fast feedback](https://tomasvotruba.com/blog/2020/01/13/why-is-first-instant-feedback-crucial-to-developers), so **everyone can enjoy work more**. So in July and August 2023 we worked hard to make our tests faster then sip of a good coffee.
---

## Why so Slow?

At first, we had to identify, *why* are our tests so slow. Let's take it step by step. The code is being parsed by php-parser, we have around 3 300 tests. That means:

* php-parser a test fixture to nodes, typically PHP class of 15 lines,
* then PHPStan decorates nodes with types
* then Rector uses registered rules and transform code
* then printer prints nodes back to string
* then printed string is compared to an expected one

<br>

**This happen 3300-times**. We try to speed up the process by removing unnecessary iterations, that helped on a single-project run. We're very greatful for [tremendous work Marcus Staab](https://staabm.github.io/2023/05/06/racing-rector.html) has done in this area.

<br>

So we were pretty sure the **bottleneck is elsewhere**. But where?

<img src="https://user-images.githubusercontent.com/924196/261989353-72ff0dfe-e881-4174-9bf0-d38d72e619e8.png" class="img-thumbnail">

<em>This is Rector 0.17, it takes **73 seconds** and **8.17 GB or memory**.</em>

<br>

The idea came in a lucky experiment. Few weeks ago I flipped [ECS from Symfony to Laravel container](https://tomasvotruba.com/blog/experiment-how-i-replaced-symfony-di-with-laravel-container-in-ecs), because it's much easier to use when it comes to CLI apps - that we downgrade to PHP 7.2 and prefix every single class.

What surprised me was this had effect on tests speed as well. [Tests went down from 0,75 s to 0,17 s](https://twitter.com/VotrubaT/status/1683576139049058304) - **that's 77 % faster**. These numbers are too small to take seriously, but it gave us a hint - maybe we could achieve similar speed up in Rector.

Even going down from 80 seconds to 21 would make contributing Rector more joyful.

<br>

The speed up was **combination of 3 changes**.

<br>

## 1. From Compiled contains to Lazy Container

In every tests, the container has to load config, register rules as a service, setup parameters and then invoke the cycle above. The Symfony container is a compiled one, so the fluent PHP config that `RectorConfig` is dumped to cached PHP file. This brings great performance on HTTP requests per second, but can put huge burden on your local on 3300 different tests. The Symfony parameter bag is tightly coupled to container, so to cache e.g. paths or skip parameter, the container cache rebuild is needed.

All we need from container is fetching autowired service and passing tagged services to collectors. We also hoped less container features = less dead-code in /vendor = faster code run.

<br>

On the other hand, the Laravel container is *lazy* - it only creates the services you need, when you need them. Rector core contains ~ 400 services. If you need to test one them, the Laravel container will create just the service with its dependency tree. Nothing else. This makes running Rector tests faster, as we always call single Rector service over and over.

We switched **the container from compiled and cached to lazy one**. This allowed us to create a shared container for all the 3300 tests. The means if a `NodeNameResolver` is injected in each of those tests, it will still be created just once and reused.

But that wasn't enough.

<br>

## 2. Identify Resettable Services

When we moved from compiled container per test case to a lazy one, we had another problem. Some services kept state and piled up configuration or cached values. E.g. collector that kept class renames was growing on every run.

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

Instead of container-coupled parameter services like in compiled container, we used static parameter provider, that we reset in `tearDown()`. This allowed us to keep the lazy container and parameter configuration separate.

<br>

## 3. Avoid new Nodes in Data Providers

Last but not least, there were 3 test cases that were related to docblock printer. They were specific by interesting behavior - when we run them standalone, it took around 80 ms. But the more tests were run before them, the longer it took. E.g. for the whole test suite there took 2-3 minutes more.

It was not in Rector code, nor in PHPStan code, nor Laravel container or any other used dependency. Well, it ones one of the dependencies - the PHPUnit.

The PHPUnit 10 made a change in data providers to require them to be static. It's probably related to some caching, because in this code it spiked 2-3 minute delay:

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

So instead of 10 seconds, the whole **test suite would run couple minutes** even on Laravel container. It has probably something to do with the [`jsonSerialize()` method of `PhpParser\NodeAbstract`](https://github.com/nikic/PHP-Parser/blob/a6303e50c90c355c7eeee2c4a8b27fe8dc8fef1d/lib/PhpParser/NodeAbstract.php#L172-L177) and PHPUnit caching.

Other value objects like PHPStan type objects can be used in data providers with no performance hit.

<br>

So, **how we fixed it**? Simply using nodes on outside the data provider:

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

* From 73 seconds to **10 seconds**

* From 8 170 MB of memory to **633 MB**

* We narrowed 8 parallel test jobs **[to single one](https://github.com/rectorphp/rector-src/pull/4827)** with 20 seconds run.

<br>

Job well done!

We know there is still space to improve the container. Do you have some experience with Laravel container performance optimization? Please, roast our [container factory](https://github.com/rectorphp/rector-src/blob/main/src/DependencyInjection/LazyContainerFactory.php) to make it faster, so Rector could be run by any PHP developer on any machine. Thank you!

<br>

Happy coding!
