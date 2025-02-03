---
id: 76
title: "Road to Hell is Paved with Strings"
perex: |
    In [7 Traits of Successful Upgrade Companies](/blog/7-traits-of-successful-upgrade-companies) we wrote about behavior patterns of companies who [make the impossible upgrade happen](/blog/success-story-of-automated-framework-migration-from-fuelphp-to-laravel-of-400k-lines-application).

    Today we look closely on an **anti-pattern that repeats in every framework**, in every custom solution and even in testing tools. A lot of CLI tools that should help us write better code also suffer from it. Framework are trying to get rid of it, but most drag mountain pile of history BC promise and so never do.

    In this post we'll look at spotting this anti-pattern, think about better way to achieve same result and share our strategy to get rid of it in our projects.
---

At first, here are 4 code examples we keep seeing in legacy project we're hired to upgrade. Maybe you'll know them:

```php
return [
    'sync' => 'autoamted',
    'aws-region' => 'us-east-1',
    'aws-access-key' => 'ENV(AWS_ACCESS_KEY)',
];
```

<br>

or

```yaml
parameters:
    paths:
        - src
        - tests

    ignoreError:
        - "#Missing type#"
```

<br>

or

```php
services:
    -
        class: "App\\Controller\\HomepageController"
        autowiret: true
```

<br>

or

```php
return [
    'connections' => [
        'sqlite' => [
            'host' => 'url:sqlite:///:memory:',
        ],
        'prefix' => '',
    ]
];
```

<br>

First snippet is a custom code, but snippets **2-4 are coupled to a project you know**. They're coupled to a documentation you've read, on Stackoverflow or GPT.

All the snippets **include tyop that would break** the functionality. Have you noticed them on first scan?

<br>

<blockquote class="blockquote text-center mt-5 mb-5">
"If we have to read documentation to understand the configuration,<br>
it sound more like hardware than software."
</blockquote>

<br>

We have to know that:

* "autowired" is a Symfony service configuration keyword
* it can be located in `services` section under the service definition
* it's not `autowire**t**` but `autowire**d**`

<br>

## Why does this Matter?

Imagine we have a flight company that handles flights between US and UK. We have quality control that checks all electronics on the plane works, that tires have good defined pressure, that the fuel is enough and so on. Everything is fully automated and we get any report on exceptional values across time. Sounds safe enough for you to fly? Also think about main business functions of airlines - to maximize profits, we have to cut costs to a minimum.

We have a fuel tank, **sealed tight with dozens of screws**. Should we check manually every screw?

What if they change the fuel for a more effective one, that accidentally speeds up rusting of marginal material that our screws are using?

<br>

**What if someone forgets?** We don't want to think about these problems. We already put our attention into [the new software that we've deployed](https://spectrum.ieee.org/how-the-boeing-737-max-disaster-looks-to-a-software-developer) last week.

<br>

## Protect your Deep Work

This is called [cognitive load](https://github.com/zakirullin/cognitive-load) and it's root of many fatal bugs in software. This is how it it works:

<img src="https://github.com/zakirullin/cognitive-load/raw/main/img/cognitiveloadv6.png" class="img-thumbnail mb-5" style="max-width: 30em">

<br>

There is a great book called [Don't Make Me Think](https://www.amazon.com/Dont-Make-Think-Revisited-Usability/dp/0321965515), that hits this problem in very entertaining way.

<br>

## "Too many Cooks spoil the Broth"

Some teams are able to upgrade their projects from PHP 5.3 to 8.3 themselves. Other teams too, but it turns into a costly 3-year project with half-team full-time effort. And some other teams are unable to do it in timely matter and stuck with "if it works, don't touch it approach" that causes ever-growing loses in software business.

The latter teams have to deal with soo many edge cases, and WTFs per day, they're pushing their abilities to the limit just to keep projects running. There is a short road to burnout from there.

That's why it matters: the code must be readable and easy to understand, even to a fresh developer... or to a fresh GPT that reads the code for the first time too.

## Enable Power of Static Analysis

Another reason is power of static analysis - in PHP we have PHPStan, static analysis [for TWIG](https://github.com/twigstan/twigstan) or [for Blade](https://github.com/bladestan/bladestan), static analysis [for Behat](https://github.com/rectorphp/swiss-knife/#7-find-unused-behat-definitions-with-static-analysis---behastan) and more.

This static analysis would warn us about 3 typos in the examples above. We'd know what we should fix it before merging the pull-request.

<br>

But what about other file formats than PHP? **That's the weak link.**

`YAML`, `ini`, `XML`, `JSON`, `NEON` or Gherkin bellow. These all are parseable formats, they're **all parsed into strings**.

```yaml
Feature: Reading code that looks like string,
    but actually calls PHP method

  Scenario: Avoid thinking about code
    Given am lazy human
    Then I delegate everything to static analysis
```

We have to change those into PHP format first. Sometimes it's one of formats the tool already handles.

Sometimes we have to come up with our own format like [in case of Behat PHP Config](https://x.com/VotrubaT/status/1882864670723412438/photo/1) - fortunatelly, it's pretty easy and straightforward with php-parser.

<img src="https://pbs.twimg.com/media/GiFFTcjXwAA_c1Z?format=jpg&name=large" class="img-thumbnail" style="max-width: 40em">

<br>
<br>
<br>

Now let's say we actually have all configs in PHP syntax. Is that good enough to **avoid legacy forever?**

```php
return [
    'sync' => 'autoamted',
    'aws-region' => 'us-east-1',
    'aws-access-key' => 'ENV(AWS_ACCESS_KEY)',
];
```

No, it's not.

* we still made a typo
* we don't know what is *configuration option* and what is *our written value*
* PHPStan is running, but silent

<br>

## Configuration Objects to the Rescue!

I'll share few examples, so you have better idea about shape we want to get in. First, let's convert our 1st example to configuration object:

```php
return AwsConfig::configure()
    ->syncAutomated()
    ->withAwsRegion(RegionList::US_EAST_1)
    ->withAwsAccessKey(EnumKey::AWS_ACCESS));
```

Now we have:

* typo-proof IDE method autocomplete ✅
* IDE autocomplete for enums ✅
* PHPStan warning us about invalid types ✅
* PHPStan reporting deprecated methods ✅

<br>

In 2024, the Laravel 11 introduced [streamlined configs](https://laravel-news.com/laravel-11-directory-structure):

```php
return Application::configure()
    ->withProviders()
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
    )
    ->withMiddleware(function (Middleware $middleware) {})
    ->withExceptions(function (Exceptions $exceptions) {})
    ->create();
```

<br>

[ECS](https://github.com/easy-coding-standard/easy-coding-standard) uses PHP config object [since 2022](https://tomasvotruba.com/blog/new-in-ecs-simpler-config):

```php
// ecs.php
use PhpCsFixer\Fixer\ListNotation\ListSyntaxFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;

return ECSConfig::configure()
    ->withPaths([__DIR__ . '/src', __DIR__ . '/tests'])
    ->withRules([
        ListSyntaxFixer::class,
    ])
    ->withPreparedSets(psr12: true);
```

<br>

In 2021, Symfony 5.3 shows state of art [autogenarated-configs](https://symfony.com/blog/new-in-symfony-5-3-config-builder-classes):

```php
// config/packages/security.php
use Symfony\Config\SecurityConfig;

return static function (SecurityConfig $security): void {
    $security->firewall('main')
        ->pattern('^/*')
        ->lazy(true)
        ->anonymous();

    $security->accessControl(['path' => '^/admin', 'roles' => 'ROLE_ADMIN']);
};
```

<br>

## Automate and Forget

Once we have PHP in place, we move to configuration object and get PHPStan with deprecated rules on board, we can forget **about the configuration syntax**. We can focus on the business logic and let the configuration be handled by the tool.

* Next time you use Symfony - [modernize configs](/blog/modernize-symfony-configs) to best shape the framework provides.
* Upgrade your Laravel project to 11 and use [streamlines configs](https://github.com/laravel/laravel/blob/master/bootstrap/app.php).
* Create a PHP wrapper for the tool that uses that old format and is error prone. Learn [php-parser](https://github.com/nikic/PHP-Parser).

<br>

Happy coding!
