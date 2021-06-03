---
id: 11
title: "Rector 0.9 Released ❄️"
perex: |
    More than 45 days have passed since the last Rector release. Since then, we pushed 292 commits in over 220 pull-requests. No wonder the most common question in issues was "when will the next Rector be released?".
    <br>
    <br>
    Today, we're proud to finally **tag and launch Rector 0.9**!
---

<img src="/assets/images/blog/2020/rector-09-contributors.png" class="img-thumbnail">


## PHP 8 Upgrade Set

The most awaited feature is **upgrade set to PHP 8**. We already wrote [Smooth Upgrade to PHP 8 in Diffs](https://getrector.org/blog/2020/11/30/smooth-upgrade-to-php-8-in-diffs) - with promoted properties, annotations to attributes, union types, and more.

We tested this set for the last 30 days, solved bugs one by one, and applied in CI on 5 PHP projects.

**How does it perform?**

<blockquote class="blockquote mt-4 mb-4 text-center">
    That feeling when you go to make coffee, <br>
    while @rectorphp upgrades your project in PHP 8...
    <footer class="blockquote-footer text-right">
        <a href="https://twitter.com/honzakuchar/status/1341777745475473411">Jan Kuchař</a>, CTO at Grifart
    </footer>
</blockquote>

Do you want to upgrade to PHP 8? It's ready!

```php
use Rector\Set\ValueObject\SetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(SetList::PHP_80);
};
```

## Debuggable Rector Prefixed

The PHAR file is a file that contains PHP code in a single RAR file, thus the PH+AR acronym. Working with PHAR in PHP has it's edge cases. Do you use a real path? [realpath in PHAR does not work](https://bugs.php.net/bug.php?id=52769). Everything has to be relative to some PHAR root. Another bunch of bugs happen [in Symfony](https://tomasvotruba.com/blog/2019/12/02/how-to-box-symfony-app-to-phar-without-killing-yourself/), thanks to `glob` and slash juggling. The PHAR itself caused over [143 issues](https://github.com/rectorphp/rector/search?q=prefixed+is%3Aissue&type=Issues) so far.

### Dump in PHAR? Forget it

Rector is designed to work with **the worst code possible**. It's improving on every release but still fails with a fatal error on new edge cases that we haven't thought of.

How would you **debug a line in prefixed Rector**? Simple and fast `dump()` on the broken line? But, how can we **edit a RAR file**? It's not possible to do directly. First, we need to unpack the file, edit it, then pack it again. The same rules apply for PHAR.

We want to make working with Rector in legacy projects more accessible. That's why [we're moving to scoped version](https://github.com/rectorphp/rector/pull/4559/files).

### Try it Now

Install path remains the same:

```bash
composer require rector/rector-prefixed --dev
```

But now you can also:

- debug with `dump()`
- modify the code in `/vendor`
- work with absolute paths in Rector rules

## Bump min version to PHP 7.3

[PHP 7.2 is out of maintenance](https://www.php.net/supported-versions.php) November 30th, and dangerous to use. That's why the minimal version for Rector was bumped to 7.3.

## Downgrade Sets

Why would anyone want to downgrade code? What a silly question, right?

There is one use case that **every package maintainer is thinking about**. We want to develop in the latest PHP - PHP&nbsp;8. But our `composer.json` also allows PHP 7.3 and 7.4. Why? Because we don't want to leave the PHP community behind.

This will soon be possible on a CI basis. Today, you can [code in PHP 7.4 and deploy to 7.1 via GitHub Actions](https://blog.logrocket.com/coding-in-php-7-4-and-deploying-to-7-1-via-rector-and-github-actions).

## PHP-version Releases

Now the fun part comes. We have downgrade sets, and we moved from PHAR to Scoped. This switch not only solves edge cases and Docker mashup with relative/absolute paths but also opens the door to per-PHP version releases. Do you need Rector for PHP 7.1 without Docker?

```bash
composer require rector/rector-php71 --dev
composer require rector/rector-php70 --dev
composer require rector/rector-php56 --dev
```

The floor is the limit.

[We're working on it](https://github.com/rectorphp/rector/pull/4447). Stay tuned!

## Upgrade from 0.8 to 0.9

Do you want to try a new version but fear changes? Check [`UPGRADE_09.md`](https://github.com/rectorphp/rector/blob/master/UPGRADE_09.md) for specific steps and go for it:

```bash
composer update rector/rector:^0.9
```

## Thank You, Contributors

Last but not least, we'd like to thank [all 0.9 contributors](https://github.com/rectorphp/rector/graphs/contributors?from=2020-11-15&to=2020-12-27&type=c) that put their keystrokes together as one.

Thank you [@samsonasik](https://github.com/samsonasik), [@leoloso](https://github.com/leoloso), [@simivar](https://github.com/simivar), [@staabm](https://github.com/staabm), [@Wirone](https://github.com/Wirone), [@HDVinnie](https://github.com/HDVinnie), [@lulco](https://github.com/lulco) and [@JanMikes](https://github.com/JanMikes).

<br>

Happy [rectoring](https://rectoring.com/)!
