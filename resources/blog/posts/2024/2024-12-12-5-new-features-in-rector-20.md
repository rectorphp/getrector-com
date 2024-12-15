---
id: 74
title: "5 New Features in Rector 2.0"
perex: |
    Rector 2 is out! We've upgraded to PHPStan 2 and PHP-Parser 5. Based on testing on several huge legacy projects, Rector now runs **10-15&nbsp;%** faster.

    We've also managed to fit in a couple of new features.
---

Let's take a look at what's new.

<br>

## 1. The `--only` Option to run 1 Rule

At the moment, the Rector repository has a `rector.php` config that enables the running of 350+ rules. What if we add a single custom rule and want to run only that one? We'd have to comment out all other rules, run Rector, and then uncomment them back. That's a lot of manual work, right?

<br>

**Now we can use the `--only` option to run only a single rule**:

```bash
vendor/bin/rector process src --only="Utils\Rector\MoveTraitsToConstructorRector"
```

Making all quotes and slashes in CLI work across all operating systems was a tough challenge. Thanks to [Christian Weiske](https://github.com/rectorphp/rector-src/pull/6441), who's done a great job on this feature.

<br>

## 2. Introducing Composer-based sets

In the wild, vendor sets like Symfony, Twig, Laravel, or Doctrine have many sets - each containing a group or rules for a specific version. Symfony has over 20 sets, but let's look at a more straightforward example - Twig:

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

This doesn't seem right for many reasons. It can lead to:

* bloated `rector.php` file
* missed new set, as we have to always add new sets as they're published
* conflicting changes as version 2.4 can remove something added in version 1.12, many years apart

<br>

Instead, **the Rector should be able to pick up the version from the installed version and provide only relevant rules**.

Fully automated, like the following:

```php
return RectorConfig::configure()
    ->withComposerBased(twig: true)
```

Currently, we provide `twig`, `doctrine`, and `phpunit` composer-based sets.

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

We've been providing [annotations to attributes upgrade](https://getrector.com/blog/how-to-upgrade-annotations-to-attributes) since PHP 8.0 day. You can enable them in `rector.php` easily:

```php
use Rector\Config\RectorConfig;

return RectorConfig::configure()
   ->withAttributesSets(symfony: true, phpunit: true, doctrine: true);
```

<br>

In reality, some packages add attribute support a bit later. E.g., Rector 2 ships with [Behat attributes](https://github.com/rectorphp/rector-src/pull/6510) contributed by [Carlos Granados](https://github.com/carlos-granados). To use them in our project, we'd have to change the config:

```diff
 use Rector\Config\RectorConfig;

 return RectorConfig::configure()
-   ->withAttributesSets(symfony: true, phpunit: true, doctrine: true);
+   ->withAttributesSets(symfony: true, phpunit: true, doctrine: true, behat: true);
```

<br>

But who has time to check if this or that package has new attribute sets? Instead, **make the method empty**:

```diff
 use Rector\Config\RectorConfig;

 return RectorConfig::configure()
-   ->withAttributesSets(symfony: true, phpunit: true, doctrine: true);
+   ->withAttributesSets();
```

Now, the Rector will pick up all attribute sets automatically.

<br>

Also, Rector will now check if the attribute **actually exists before it adds it**. E.g. `#[Route]` attribute was not added until Symfony 5.2. If you're running Symfony 5.1, Rector will not make any changes to your code.


<br>

## 5. Leaner Custom Rules

Last but not least, we've collected feedback from custom rule creators. We also create many custom rules for our clients. Some of them are temporary, and others fix simple elements. Still, we always have to fill `getRuleDefinition()` with dummy data to make Rector happy.

In reality, this method is used only by Rector core rules for the [Find rule](https://getrector.com/find-rule) page.

Saying that we no longer need to write tedious `getRuleDefinition()`. Now you can finally drop this method:

```diff
 use Rector\Rector\AbstractRector;
-use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
-use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

 Final class SimpleRector extends AbstractRector
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
