---
id: 60
title: "Rector 1.0 is Here"
perex: |
    The stable Rector version is here. It was about time and we've done all planned changes by the end of 2023.
---

What could be better time and place to release a stable Rector than live on stage during talk:

<blockquote class="twitter-tweet"><p lang="en" dir="ltr">.<a href="https://twitter.com/VotrubaT?ref_src=twsrc%5Etfw">@VotrubaT</a> just release 1.0 of Reactor 🙌🙌 <a href="https://twitter.com/hashtag/LaraconEU?src=hash&amp;ref_src=twsrc%5Etfw">#LaraconEU</a> <a href="https://t.co/Z0D9omHiZF">pic.twitter.com/Z0D9omHiZF</a></p>&mdash; Christoph Rumpel 🤠 (@christophrumpel) <a href="https://twitter.com/christophrumpel/status/1754862081600332020?ref_src=twsrc%5Etfw">February 6, 2024</a></blockquote>
<br>

While this release brings stable API, it will be easier to stay up to date as well. The 1.x versioning behaves as expected with `composer update` (compared to special 0.x).

Our main focus **is on improving developers experience**. **This release brings new features that help with custom rules writing, adding Rector to CI and adding Rector to any legacy project in general**.

<br>

Some features are partially available in previous version, but we'd like to highligh them because since 1.0 you can use them all together.

<br>


## Zen Config with Autocomplete

If you run Rector for the first time, it will create a `rector.php` config with your project paths for you. In past we used various class constants references to add commonly used rule sets. This required knowledge about these classes and was often missed.

We've changed this to work with single configuration class. It provides autocomplete for available sets, including attributes:

```php
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPreparedSets(codeQuality: true, codingStyle: true)
    ->withAttributesSets(symfony: true, doctrine: true)
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->withRootFiles();
```

<br>

## PHP Sets Automated

To keep up to date with you PHP, now you can use single method:

```php
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPhpSets();
```

<br>

It learns about PHP version from your `composer.json`:

```json
{
    "require": {
        "php": "^8.0"
    }
}
```

...and will always keep sync with your required PHP version. No need to double check `rector.php` configuration anymore.

<br>


## Streamline Integration to Projects

We're also adding 2 experimental methods, that make Rector integration to new projects easier. Before, you could run whole  type declaration or read code set, see 1000 changed files and rather close it being overwhelmed. Instead, we want to take it slow, as we do with our custom upgrades as well:

```php
use Rector\Config\RectorConfig;

return RectorConfig::configure()
     ->withTypeCoverageLevel(10)
     ->withDeadCodeLevel(10)
```

Now you can improve your code base one rule at a time. The rules are sorted from the easiest to integrate, e.g. add `void` to closure, to more complex one. That way you can improve your code base in your own pace. We're collecting feedback on rule order, so the levels will likely change.

<br>

## New and Improved Commands

We added a new command to generate bare custom rule files and structure for you:

```bash
vendor/bin/rector custom-rule
```

Call the command, type the rule name and rule, its test and composer autoload is generated for you. So you can focus on the contents of `refactor()` method.

<br>

We also improved the `setup-ci` command, that generates Github and Gitlab CI setup files, so you can let Rector work for you:

```bash
vendor/bin/rector setup-ci
```

The command handles generic setup for you and then guides you to register needed access.

<br>

Last but not least, we've **updated [the book to run Rector 1.0](https://leanpub.com/rector-the-power-of-automated-refactoring/)** as well, so you can enjoy the latest features and improvements.

<br>

Enjoy first major Rector release and let us know how you use it!

<br>

Happy coding!
