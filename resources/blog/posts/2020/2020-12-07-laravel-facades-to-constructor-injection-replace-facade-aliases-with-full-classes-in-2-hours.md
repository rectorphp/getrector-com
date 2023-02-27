---
id: 8
title: "Laravel Facades to Constructor Injection: Replace Facade Aliases with Full Classes in 2 hours"
perex: |
    Laravel facades are known as [static service locators](https://sergeyzhuk.me/2016/05/27/laravel-facades/). The idea is get any service anywhere, which comes very handy for project bootstrapping.
    <br>
    <br>
    Around Laravel 6, released in March 2019, the Laravel community [started](https://stackoverflow.com/questions/49138428/avoid-laravel-facade-on-controller) [moving away](https://github.com/laravel/ideas/issues/1508) [from](https://programmingarehard.com/2014/01/11/stop-using-facades.html/) [facades](https://www.freecodecamp.org/news/moving-away-from-magic-or-why-i-dont-want-to-use-laravel-anymore-2ce098c979bd/#facades) towards **clearly typed constructor injection**.
    <br>
    <br>
    Today we'll take 1st step to make it happen.

updated_at: '2022-04'
updated_message: |
    Since **Rector 0.12** a new `RectorConfig` is available with simpler and easier to use config methods.
---

It was a big surprise for us that it's not only external critics of Laravel but also from inside the community itself.
There is [a proposal to remove facades completely in Laravel 6](https://github.com/laravel/ideas/issues/1508):

<img src="/assets/images/blog/2020/laravel-facades-6.png" class="img-thumbnail">

<br>

But as we well know, programmers are lazy:

<blockquote class="blockquote mt-5 mb-5 text-center">
    "Give me the tools to solve my problem, and I'll consider it.
    <br>
    Give me an extra work, and I'll pass."
</blockquote>

So, in the end, the discussion was closed, and nothing has changed. Yet, the spark that started a fire...

<img src="/assets/images/blog/2020/laravel-remove-class-alias.png" class="img-thumbnail">


## What the... Alias?

Aliases are defined in `config/app.php` under `alias` key. They're basically converted to:

```php
// 'original class', 'alias'
class_alias('Some\LongerClass\App', 'App');
```

That means these 2 lines are identical:

```php
App::run();
Some\LongerClass\App::run();
```

2 ways to do 1 thing is a code smell. It's also the low handing fruit we can make **colossal leap to make our code base less magical**.

As a bonus, we'll save few in `config/app.php`:

<img src="/assets/images/blog/2020/laravel-facades-alias-drop.png" class="img-thumbnail">

## 3 Steps to Remove Facade Aliases

Class alias is basically inverted class rename. So all we need to do si rename short classes (`App`) to long ones (`Some\LongerClass\App`), e.g.:

<img src="/assets/images/blog/2020/laravel-fqn.png" class="img-thumbnail mb-3">

1. Install Rector

```bash
composer require rector/rector --dev

# creates "rector.php"
vendor/bin/rector init
```

2. Configure `rector.php`

Look how Laravel is helping us here. Just copy paste lines from `config/app.php` → `aliases`:

```php
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Config\RectorConfig;

return function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(RenameClassRector::class, [
        'App' => 'Illuminate\Support\Facades\App',
        'Artisan' => 'Illuminate\Support\Facades\Artisan',
        'Auth' => 'Illuminate\Support\Facades\Auth',
        'Blade' => 'Illuminate\Support\Facades\Blade',
        'Broadcast' => 'Illuminate\Support\Facades\Broadcast',
        'Bus' => 'Illuminate\Support\Facades\Bus',
        'Cache' => 'Illuminate\Support\Facades\Cache',
        'Config' => 'Illuminate\Support\Facades\Config',
        // ...
    ]);
};
```

3. Run Rector

```bash
vendor/bin/rector process src
```

That's it!

<br>

## Blade Templates?

What are Blade templates in simple words? PHP files with syntax sugar.

Most Rector rules or static analysis would fail here, but **Rector can rename classes even in non-standard PHP files** like Blade, TWIG, Latte, Neon, or YAML. We rarely want to rename a class in PHP code and keep the old name in configs and templates.

<img src="/assets/images/blog/2020/laravel-facades-alias-blade.png" class="img-thumbnail mb-3">

Blade templates are covered too!

<br>


## Real Case Study with Eonx

You're probably thinking: "Demo always looks nice. It's designed to look nice. But what about a real private project?"
We're glad you doubt.

Recently, we applied this refactoring on a project of size 250 000-750 000 lines of code, in cooperation with Australian company, [Eonx](https://eonx.com/).

Here are all the pull-requests, we had to make:

<img src="/assets/images/blog/2020/laravel-facades-2-days-work.png" class="img-thumbnail">

<em>The biggest pull-request has changed 45 600 lines.</em>

<br>

### The Numbers

- we changed ~65 000 lines of code
- it took ~15 hours of Rector configuration work and research (= the insights in this post)
- and 4 hours of coding work and running Rector

Now the same work on the same project would take **2 hours in total**. And we believe you can do it for your project too!

<br>

Happy refactoring!
