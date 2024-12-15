---
id: 74
title: "5 New Features in Rector 2.0"
perex: |
<<<<<<< HEAD
<<<<<<< HEAD
    Rector 2 is out! We've upgraded to PHPStan 2 and PHP-Parser 5. Based on testing on several huge legacy projects, Rector now runs **10-15&nbsp;%** faster.

    We've also managed to fit in a couple of new features.
=======
    Rector 2 is out! We've upgraded to PHPStan 2 and PHP-Parser 5. Based on testing on a couple huge legacy projects, Rector now runs **10-15&nbsp;%** faster.

    We've also managed to fit in couple new features.
>>>>>>> 1969a1e5 ([post] rector 2)
=======
    Rector 2 is out! We've upgraded to PHPStan 2 and PHP-Parser 5. Based on testing on several huge legacy projects, Rector now runs **10-15&nbsp;%** faster.

    We've also managed to fit in a couple of new features.
>>>>>>> b2713766 (gram)
---

Let's take a look at what's new.

<br>

## 1. The `--only` Option to run 1 Rule

<<<<<<< HEAD
<<<<<<< HEAD
At the moment, the Rector repository has a `rector.php` config that enables the running of 350+ rules. What if we add a single custom rule and want to run only that one? We'd have to comment out all other rules, run Rector, and then uncomment them back. That's a lot of manual work, right?
=======
At the moment, the Rector repository has a `rector.php` config that enables runs 350+ rules. What if we add a single custom rule and want to run only that one? We'd have to comment out all other rules, run Rector, and then uncomment them back. That's a lot of manual work, right?
>>>>>>> 1969a1e5 ([post] rector 2)
=======
At the moment, the Rector repository has a `rector.php` config that enables the running of 350+ rules. What if we add a single custom rule and want to run only that one? We'd have to comment out all other rules, run Rector, and then uncomment them back. That's a lot of manual work, right?
>>>>>>> b2713766 (gram)

<br>

**Now we can use the `--only` option to run only a single rule**:

```bash
vendor/bin/rector process src --only="Utils\Rector\MoveTraitsToConstructorRector"
```

<<<<<<< HEAD
<<<<<<< HEAD
Making all quotes and slashes in CLI work across all operating systems was a tough challenge. Thanks to [Christian Weiske](https://github.com/rectorphp/rector-src/pull/6441), who's done a great job on this feature.
=======
It's was a tough challenge to make all quotes and slashes in CLI work across all operating systems.

Thanks to [Christian Weiske](https://github.com/rectorphp/rector-src/pull/6441) who's done a great job on this feature.
>>>>>>> 1969a1e5 ([post] rector 2)
=======
Making all quotes and slashes in CLI work across all operating systems was a tough challenge. Thanks to [Christian Weiske](https://github.com/rectorphp/rector-src/pull/6441), who's done a great job on this feature.
>>>>>>> b2713766 (gram)

<br>

## 2. Introducing Composer-based sets

<<<<<<< HEAD
<<<<<<< HEAD
In the wild, vendor sets like Symfony, Twig, Laravel, or Doctrine have many sets - each containing a group or rules for a specific version. Symfony has over 20 sets, but let's look at a more straightforward example - Twig:
=======
In the wild, the vendor-sets like Symfony, Twig, Laravel or Doctrine, have many sets - each containing a group or rules for specific version. Symfony has over 20 sets, but let's look at simpler example - Twig:
>>>>>>> 1969a1e5 ([post] rector 2)
=======
In the wild, vendor sets like Symfony, Twig, Laravel, or Doctrine have many sets - each containing a group or rules for a specific version. Symfony has over 20 sets, but let's look at a more straightforward example - Twig:
>>>>>>> b2713766 (gram)

```php
use Rector\Config\RectorConfig;

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

<<<<<<< HEAD
<<<<<<< HEAD
This doesn't seem right for many reasons. It can lead to:
=======
This is wrong for many reasons. It can lead to:
>>>>>>> 1969a1e5 ([post] rector 2)
=======
This doesn't seem right for many reasons. It can lead to:
>>>>>>> b2713766 (gram)

* bloated `rector.php` file
* missed new set, as we have to always add new sets as they're published
* conflicting changes as version 2.4 can remove something added in version 1.12, many years apart

<br>

<<<<<<< HEAD
<<<<<<< HEAD
Instead, **the Rector should be able to pick up the version from the installed version and provide only relevant rules**.

Fully automated, like the following:
=======
Instead, **Rector should be able to pick up version from installed version, and provide only relevant rules**.

Fully automated, like following:
>>>>>>> 1969a1e5 ([post] rector 2)
=======
Instead, **the Rector should be able to pick up the version from the installed version and provide only relevant rules**.

Fully automated, like the following:
>>>>>>> b2713766 (gram)

```php
return RectorConfig::configure()
    ->withComposerBased(twig: true)
```

<<<<<<< HEAD
<<<<<<< HEAD
Currently, we provide `twig`, `doctrine`, and `phpunit` composer-based sets.
=======
Currently we provide `twig`, `doctrine` and `phpunit` composer-based sets.
>>>>>>> 1969a1e5 ([post] rector 2)
=======
Currently, we provide `twig`, `doctrine`, and `phpunit` composer-based sets.
>>>>>>> b2713766 (gram)

If you want to **know how it works behind the scenes**, check [this dedicated post](/blog/introducing-composer-version-based-sets).

<br>

## 3. Polyfill Packages by Default

We had a special configuration to enable [polyfill packages](https://github.com/symfony/polyfill). That way we can get PHP 8.x features early on PHP 7.x codebase. Yet, not many people knew about it and missed it. Also, polyfill packages are already defined in the `composer.json`, so it doesn't make sense to "enable" them again `rector.php`.

We've decided to include polyfills by default when `->withPhpSets()` is called. So you can drop the extra method:

```diff
 use Rector\Config\RectorConfig;

 return RectorConfig::configure()
-    ->withPhpPolyfill()
     ->withPhpSets();
```

It works for all `->withPhp*Sets()` too.

<br>

## 4. Smarter Annotations to Attributes sets

<<<<<<< HEAD
<<<<<<< HEAD
We've been providing [annotations to attributes upgrade](https://getrector.com/blog/how-to-upgrade-annotations-to-attributes) since PHP 8.0 day. You can enable them in `rector.php` easily:
=======
We're proving [annotations to attributes upgrade](https://getrector.com/blog/how-to-upgrade-annotations-to-attributes) since PHP 8.0 day one. You can enable them in `rector.php` easily:
>>>>>>> 1969a1e5 ([post] rector 2)
=======
We've been providing [annotations to attributes upgrade](https://getrector.com/blog/how-to-upgrade-annotations-to-attributes) since PHP 8.0 day. You can enable them in `rector.php` easily:
>>>>>>> b2713766 (gram)

```php
use Rector\Config\RectorConfig;

return RectorConfig::configure()
   ->withAttributesSets(symfony: true, phpunit: true, doctrine: true);
```

<br>

<<<<<<< HEAD
<<<<<<< HEAD
In reality, some packages add attribute support a bit later. E.g., Rector 2 ships with [Behat attributes](https://github.com/rectorphp/rector-src/pull/6510) contributed by [Carlos Granados](https://github.com/carlos-granados). To use them in our project, we'd have to change the config:
=======
In reality, some packages add attribute support a bit later. E.g. Rector 2 ships with [Behat attributes](https://github.com/rectorphp/rector-src/pull/6510) contributed by [Carlos Granados](https://github.com/carlos-granados). To use them in our project, we'd have to change the config:
>>>>>>> 1969a1e5 ([post] rector 2)
=======
In reality, some packages add attribute support a bit later. E.g., Rector 2 ships with [Behat attributes](https://github.com/rectorphp/rector-src/pull/6510) contributed by [Carlos Granados](https://github.com/carlos-granados). To use them in our project, we'd have to change the config:
>>>>>>> b2713766 (gram)

```diff
 use Rector\Config\RectorConfig;

 return RectorConfig::configure()
-   ->withAttributesSets(symfony: true, phpunit: true, doctrine: true);
+   ->withAttributesSets(symfony: true, phpunit: true, doctrine: true, behat: true);
```

<br>

<<<<<<< HEAD
<<<<<<< HEAD
But who has time to check if this or that package has new attribute sets? Instead, **make the method empty**:
=======
But who has time to keep checking if this or that package has new attribute sets. Instead, **make the method empty**:
>>>>>>> 1969a1e5 ([post] rector 2)
=======
But who has time to check if this or that package has new attribute sets? Instead, **make the method empty**:
>>>>>>> b2713766 (gram)

```diff
 use Rector\Config\RectorConfig;

 return RectorConfig::configure()
-   ->withAttributesSets(symfony: true, phpunit: true, doctrine: true);
+   ->withAttributesSets();
```

<<<<<<< HEAD
<<<<<<< HEAD
Now, the Rector will pick up all attribute sets automatically.
=======
Now Rector will pick up all attribute sets automatically.
>>>>>>> 1969a1e5 ([post] rector 2)
=======
Now, the Rector will pick up all attribute sets automatically.
>>>>>>> b2713766 (gram)

<br>

Also, Rector will now check if the attribute **actually exists before it adds it**. E.g. `#[Route]` attribute was not added until Symfony 5.2. If you're running Symfony 5.1, Rector will not make any changes to your code.


<br>

## 5. Leaner Custom Rules

<<<<<<< HEAD
<<<<<<< HEAD
Last but not least, we've collected feedback from custom rule creators. We also create many custom rules for our clients. Some of them are temporary, and others fix simple elements. Still, we always have to fill `getRuleDefinition()` with dummy data to make Rector happy.

In reality, this method is used only by Rector core rules for the [Find rule](https://getrector.com/find-rule) page.

Saying that we no longer need to write tedious `getRuleDefinition()`. Now you can finally drop this method:
=======
Last but not least, we've collected feedback from custom rule creators. We also create many custom rules for our clients. Some of them are temporary, other are fixing simple elements. Still, we always have to fill `getRuleDefinition()` with dummy data, to make Rector happy. In reality, this method is used only by Rector core rules for [Find rule](https://getrector.com/find-rule) page.

Saying that, we no longer need to write tedious `getRuleDefinition()`. These methods can be finally dropped:
>>>>>>> 1969a1e5 ([post] rector 2)
=======
Last but not least, we've collected feedback from custom rule creators. We also create many custom rules for our clients. Some of them are temporary, and others fix simple elements. Still, we always have to fill `getRuleDefinition()` with dummy data to make Rector happy.

In reality, this method is used only by Rector core rules for the [Find rule](https://getrector.com/find-rule) page.

Saying that we no longer need to write tedious `getRuleDefinition()`. Now you can finally drop this method:
>>>>>>> b2713766 (gram)

```diff
 use Rector\Rector\AbstractRector;
-use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
-use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

<<<<<<< HEAD
<<<<<<< HEAD
 Final class SimpleRector extends AbstractRector
=======
 final class SimpleRector extends AbstractRector
>>>>>>> 1969a1e5 ([post] rector 2)
=======
 Final class SimpleRector extends AbstractRector
>>>>>>> b2713766 (gram)
 {
-    public function getRuleDefinition(): RuleDefinition
-    {
-        return new RuleDefinition('// @todo fill the description', [
-            new CodeSample('...', '...'),
-        ]);
-    }

     // valuable code starts here
 }
```


See the [upgrade guide](https://github.com/rectorphp/rector/blob/main/UPGRADING.md) for full details.

<br>

Happy coding!
<<<<<<< HEAD
<<<<<<< HEAD
=======

>>>>>>> 1969a1e5 ([post] rector 2)
=======
>>>>>>> b2713766 (gram)
