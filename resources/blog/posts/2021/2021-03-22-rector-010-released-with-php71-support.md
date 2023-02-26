---
id: 20
title: "Rector 0.10 Released - with PHP 7.1 Support"
perex: |
    Today we're releasing Rector that brings the most significant improvement for usability yet. It took 2 months of hard work of our team and Rector community, but we're  here.
    <br>
    <br>
    What is new, and what makes your life easier?

since_rector: 0.10
---

February and March took the Rector repository by storm. The [average pulse](https://github.com/rectorphp/rector/pulse/monthly) is around ~100 PRs, but this month it was **amazing 188 PRpM**.

<img src="https://user-images.githubusercontent.com/924196/111969654-c4043800-8afa-11eb-9121-ef1448d2ce37.png" class="img-thumbnail">

<br>

Today we're happy to share the fruits of this extensive work with you in Rector 0.10 release.

<br>

What is in the package?

<br>

## Static Reflection

This is one of the most extensive improvements we had in years. So big we've dedicated its post [Legacy Refactoring made Easy with Static Reflection](/blog/2021/03/15/legacy-refactoring-made-easy-with-static-reflection).


## Rector on PHP 7.1 and 7.2 without Docker

Exactly three months ago, we've released [Rector 0.9](/blog/2020/12/28/rector-09-released), which bumped the min PHP version from 7.1 to 7.3. That forced lot of legacy projects to go on the Docker path. If the project had Docker already, it probably wouldn't be in a state of legacy in the first place.

To compensate that, we introduced "downgrade sets". Sets that **you can turn your PHP 8 project into PHP 7.1**.

<img src="https://user-images.githubusercontent.com/924196/111987643-c02ee080-8b0f-11eb-87c4-b2733e7dfc8e.png" class="img-thumbnail">

<br>

Three months ago, it was only an idea. Today, we're happy to eat our own dog food. We've downgraded **Rector from 7.3 to 7.1**. The downgraded code is published in [rectorphp/rector-prefixed](https://github.com/rectorphp/rector-prefixed) repository.

Use it like this:

```bash
php --version
# PHP 7.1

composer require symfony/console:^2.8

composer require rector/rector-prefixed:^0.10 --dev
```

The big advantage of this approach is that code is **still editable in the vendor**. Do you have any troubles or debugging your own Rector rule? Edit `vendor/rector/rector-prefixed` code like your own.

<br>

Are you interested in workflow implementation? [See pull-request](https://github.com/rectorphp/rector/pull/5880/files)

<br>

We've reached PHP 7.1 and it's a great success. The next most wanted legacy PHP version is PHP 5.6. Can we downgrade Rector there too?


## 6 Standalone Project Packages

Let's be honest. The Rector repository got a bit fat around the belly over the years. It got out of shape, collecting too much clutter at once. Rector included every framework right in the core. Do you use Symfony? There is also CakePHP, Doctrine and Laravel.

It's like asking for Vietnamese translation for Bum Bo Nam Bo and getting 30 other languages as a bonus.

Grouping those projects together also **created a barrier for contributors**. Too much code to handle if you wanted to add a single Symfony rule.
Contrary to that, we can see [drupal-rector](https://github.com/palantirnet/drupal-rector) or [typo3-rector](https://github.com/sabbelasichon/typo3-rector) Rector community packages that focus on single project.

Saying that, we've decided to **make this simpler for you and created per-project packages**:

- [rector-symfony](https://github.com/rectorphp/rector-symfony)
- [rector-phpunit](https://github.com/rectorphp/rector-phpunit)
- [rector-doctrine](https://github.com/rectorphp/rector-doctrine)
- [rector-cakephp](https://github.com/rectorphp/rector-cakephp)
- [rector-laravel](https://github.com/rectorphp/rector-laravel)
- [rector-phpoffice](https://github.com/rectorphp/rector-phpoffice)
- [rector-downgrade-php](https://github.com/rectorphp/rector-downgrade-php)

Now **it's easier to contribute**, e.g., to Nette package, because you only have to work with Nette-specific code, nothing else.

<br>

This will also lead to:

- **more stable core Rector API**, as many packages depend on it now
- faster testing
- inspiration for community-packages

<br>

These packages are included in `rector/rector` for now, so the [prefixed and downgraded](https://github.com/rectorphp/rector-prefixed) version can be used for any project.

## Simpler Test Case

We collected feedback from Rector community developers about testing - custom methods were setting a parameter, for setting a php version, etc. It wasn't apparent how to use a test. We made this simple in Rector 0.10:

```php
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class SomeRectorTest extends AbstractRectorTestCase
{
    // test provided fixtures

    // provide fixtures

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
```

What you put in `config/configured_rule.php`, will happen.
A single test case, single config, the syntax you know from `rector.php` is there.

<br>

Happy coding!
