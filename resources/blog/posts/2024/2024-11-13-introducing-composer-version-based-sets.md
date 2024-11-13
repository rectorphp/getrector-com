---
id: 73
title: "Introducing Composer Version-Based Sets"
perex: |
    Packages that ship a lot of versions can have a lot of sets to apply. For, twig/twig has 6 sets in Rector, a couple for v1 and a couple for v2. What about v3? We must always check for our locally installed version and then keep `rector.php` up to date.

    This could lead to errors as we run sets with new features from v3 that we don't have yet.
---

At the moment, we have to add sets manually one by one to `rector.php`:

```php
return RectorConfig::configure()
    ->withSets([
        \Rector\Symfony\Set\TwigSetList::TWIG_112,
        \Rector\Symfony\Set\TwigSetList::TWIG_127,
        \Rector\Symfony\Set\TwigSetList::TWIG_134,
        \Rector\Symfony\Set\TwigSetList::TWIG_20,
        \Rector\Symfony\Set\TwigSetList::TWIG_24,
    ])
```

<br>

This doesn't seem right for a couple of reasons:

* we have to always check if there is a new set we should add here
* if we use Twig 2, there is no point in running Twig 1 set - they also may cause an error as syntax evolves
* if we use Twig 1.20, we don't want 1.40 sets to run as they might break the code

### What should happen instead?

* Rector should look into installed `composer.json` version of `twig/twig`
* then check all Twig sets and find those that make sense to apply
* run those

If we upgrade to Twig 3 later on, Rector should pick up sets for Twig 3 for us. So, we don't maintain the `rector.php` at all.

### Introducing `withComposerBased()`

```php
return RectorConfig::configure()
    ->withComposerBased(twig: true);
```

<br>

You can drop all sets from above and use this single parameter.
Currently, we support Twig, PHPUnit, and Doctrine. Support for Symfony and Laravel is coming soon.

<br>

## How does it work?

Rector will go through all Twig sets and check our installed version in `vendor/composer/installed.json`. Then, it finds all sets relevant to our specific version.

If you're writing your custom extension for your community, you'll want to create your own `SetProvider` to handle complexity for your users.

Let's look at a real example for [`TwigSetProvider`](https://github.com/rectorphp/rector-symfony/blob/main/src/Set/SetProvider/TwigSetProvider.php) from the `rector-symfony` package:

```php
namespace Rector\Symfony\Set\SetProvider;

use Rector\Set\Contract\SetInterface;
use Rector\Set\Contract\SetProviderInterface;
use Rector\Set\ValueObject\ComposerTriggeredSet;
use Rector\Set\ValueObject\Set;

final class TwigSetProvider implements SetProviderInterface
{
    /**
     * @return SetInterface[]
     */
    public function provide(): array
    {
        return [
            new ComposerTriggeredSet(
                'twig',
                'twig/twig',
                '1.27',
                __DIR__ . '/../../../config/sets/twig/twig127.php'
            ),
            new ComposerTriggeredSet(
                'twig',
                'twig/twig',
                '2.0',
                __DIR__ . '/../../../config/sets/twig/twig20.php'
            ),
            // ...
        ];
    }
}
```

<br>

Setup is straightforward - define the version and path to the set:

```php
namespace Rector\Set\ValueObject;

final class ComposerTriggeredSet
{
    public function __construct(
        private string $groupName,
        private string $packageName,
        private string $version,
        private string $setFilePath
    ) {
    }

    // ...
}
```

* The `$groupName` is key in `->withComposerBased()`:
* The `$packageName` is the composer package name.
* `$version` is the minimal version to trigger the set
* and `$setFilePath` is the path to the Rector config with rules as we know it

<br>

In the near future, community packages like [Laravel](https://github.com/driftingly/rector-laravel) will have their own `SetProvider` classes. To get their latest upgrade sets, all you'll have to do is add one param:

```php
return RectorConfig::configure()
    ->withComposerBased(laravel: true);
```


<br>

Happy coding!
