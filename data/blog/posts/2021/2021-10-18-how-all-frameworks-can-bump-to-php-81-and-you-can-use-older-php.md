---
id: 28
title: "How all Frameworks can Bump to PHP 8.1 and You can Keep Using Older PHP"
perex: |
    Imagine **hypothetical situation**, when both new major Symfony and Laravel are released in December 2021. We'll have already PHP 8.1 out by that time. There is a lot of positive vibes about new PHP versions in the last year, so let's say the frameworks will take brave leap forward.
    <br><br>
    [Symfony 6](https://symfony.com/releases/6.0) and and [Laravel 9](https://blog.laravel.com/laravel-9-release-date) will require PHP 8.1 as minimal version in their `composer.json`.
    <br><br>
    How would you react to such a move? What if you could keep using your current PHP version while using Symfony 6 or Laravel 9?
---

## Developers: "Please, Don't Force us to Upgrade"

The day has finally come. The new Symfony and Laravel 9 are released. We want to enjoy the latest features, so we bump our `composer.json`:

```diff
 {
     "require": {
        "php": "8.0",
-       "symfony/console": "^5.3"
+       "symfony/console": "^6.0"
     }
 }
```

<br>

And run composer to update dependencies:

```bash
composer update
```

<br>

What happens?

*"Could not install "symfony/console" packages, because it requires PHP 8.1.<br>It does not meet your constraint of PHP 8.0."*

This doesn't look good. Bumping to PHP 8.1 since day one might be a bit extreme, but **this situation happens in every PHP version bump**. No matter how small it is. Even if you bump from PHP 7.4 to 8.0:

<img src="/assets/images/blog/2021/downgrade_fail_version.gif" alt="" style="max-width: 35em" class="img-thumbnail">

There are always thousands of packages and projects, that can't support the new PHP version you've decided to use.

## Maintainers: "We need new PHP Features"

On the other hand, there is no point in maintaining PHP 7.1, 7.2, 7.3 and 7.4. The most of these do not have security updates:

<a href="https://www.php.net/supported-versions.php">
    <img src="/assets/images/blog/2021/downgrade_php_versions.png" alt="" style="max-width: 35em" class="img-thumbnail">
</a>

But most importantly, if we support the old PHP version, we can **say good bye to features** like:

* typed properties
* promoted properties
* `match()`
* enums
* `#[attributes]`
* union types
* intersection types

Every package maintainer wants these features in their code. That's why want to bump to PHP 8.1, not only in open-source packages, but also private projects.

## Developers versus Maintainers

The project maintainers wants to bump to the PHP 8.1, to enjoy the latest features. On the contrary, developers who use packages want support for their PHP version, so they're not forced to upgrade PHP.

<img src="/assets/images/blog/2021/matrix-versus.jpg" alt="" style="max-width: 35em" class="img-thumbnail">
<em>Two sides against each other. Humans versus machines.</em>

<br>

Which of them is evil and which of them is good? Depends on what side of the barricade do you stand.

## Is The Peace Possible?

Evolution always starts with an insane question. What if we could move from one place to another in speed 100 km/h in tunnels under the city? Now we have trains and metro and consider such question stupid.

When we see 2 camps above, we think of the world that bring them together.

<blockquote class="blockquote text-center mt-5 mb-5">
"What if you could keep using your current PHP version<br>
while using Symfony 6 or Laravel 9?"
</blockquote>

To start conflict resolution, we should look not at the differences, but **at shared goals**. What do both camps want?

* fun to code
* 0 maintenance

<img src="/assets/images/blog/2021/downgrade_peace.jpg" alt="" style="max-width: 35em" class="img-thumbnail">

These both can be achieved. If we could add one step to the release workflow.

## Introducing Release Downgrades

Let's say Symfony 6 or Laravel 9 is released with minimum of PHP 8.1. How can we change the release process so in the end, even developers with PHP 8.0 can use them?

Typical release of new tag from maintainer to developer downloading the package has 5-step lifecycle:

* tag locally
* push tag to remote repository
* let remote repository push the tag to Packagist via git hook
* let user require new version in `composer.json`
* find the tag in Packagist and download the zip from GitHub

When we require `symfony/console:6.0.0` in our code, we all get the same git hash version in `/vendor`.

## PHP-version Tailored Releases

The release process runs usually on GitHub, where also run GitHub Actions. That's where we can add step **that releases different PHP** versions of the same code:

```bash
php --version
# php 8.1
composer require symfony/console 6.0
```

Successfully installed 👍

<br>

```bash
php --version
# php 8.0
composer require symfony/console 6.0
```

Successfully installed 👍

<br>

The Symfony 6.0 requires at least PHP 8.1. How is it possible we installed it on PHP 8? If we look closer, we'll see these are 2 different tags packages:

- Symfony 6.0.0.81 for PHP 8.1
- Symfony 6.0.0.80 for PHP 8.0

The last 2 numbers in the tag stand for PHP version of the code.

**The goal** is to have the same features in both projects that use different PHP version. We do 👍

## Downgrade in GitHub Action

Where is the trick here? The PHP 8.1 release is as we know it, we just tag it 6.0.0.81 instead of 6.0.0.

The PHP 8.0 release has one extra step - downgrade code to PHP 8.0

```diff
 * tag locally
+* downgrade to PHP 8.0
 * push tag to remote repository
 * let remote repository push the tag to Packagist via git hook
 * let user require new version in `composer.json`
 * find the tag in Packagist and download the zip from GitHub
```

<br>

We create a `rector-downgrade.php` config with downgrade set:

```php
use Rector\Core\Configuration\Option;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Set\ValueObject\DowngradeLevelSetList;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(DowngradeLevelSetList::DOWN_TO_PHP_71);
};
```

<br>

Run Rector on the source code with this config:

```bash
vendor/bin/rector process /src --config rector-downgrade.php
```

<br>

After Rector finishes, the code in `/src` will have **PHP 8.0 syntax**. The release workflow will push it and tag under `6.0.0.80`.

**This way we can keep using our current PHP version, and maintainers can bump to the latest PHP ever.**

<br>

Happy coding!
