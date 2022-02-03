---
id: 31
title: "Success Story of Automated Framework Migration from FuelPHP to Laravel of 400k+lines Application"
perex: |
    Today, I'm very excited to talk about the full story of our successful automated framework migration how Rector saved our product by refactoring our 400k+lines PHP web application!
---

*This is an guest post by [rajyan](https://twitter.com/unagiunag), who used Rector to migrate an extensive PHP application from FuelPHP to Laravel.*

<br>

<blockquote class="twitter-tweet"><p lang="en" dir="ltr">For some framework switch is impossible. <br>Others can do it with zero downtime 👏 <a href="https://t.co/votwydILzX">https://t.co/votwydILzX</a></p>&mdash; Rector (@rectorphp) <a href="https://twitter.com/rectorphp/status/1479052818422116352?ref_src=twsrc%5Etfw">January 6, 2022</a></blockquote>

## Summary of the Migration Project

Our application was a monolithic application consisting of the backend of web service, native App API, intra-company support tools, and batch jobs for web and app.

* **Framework**: FuelPHP → Laravel
* **PHP Version**: PHP 7.0 → PHP 7.4
* Application:
    * Released in 2015/11
    * 2000+ PHP files, 400k+ lines of PHP codes
* **Time Schedule**
    * 2021/01-11
    * Migrated internal tools at 2021/09 (QA from 2021/07 ~)
    * Start canary release of Web and API from 2021/11/1 (QA from 2021/08 ~)
    * Switched to Laravel with 100 % release at 2021/11/16
* **Team Members**
    * 1~2 engineers + 1 senior engineer for advice
* **Special notes**
    * Running the migration and developing new features at the same time
    * Zero downtime by releasing old and new environments in the canary release

## Why did we Choose Rector?

Why did we decide to automate the migration? The reason was simple.

Our application was too large to migrate manually, and automation was needed to make the migration successful.
Even more, we estimated that it might take about a year for migration without automation, even if all team members worked for migration.

**If it's the same speed as human work, why not try something new that might be faster?**

## Fully Automated Migration

At first, we were using Rector only to convert DB query builders of FuelPHP to Laravel and manually modify controllers, configs, "Facades" (~= "Classes" in FuelPHP).
However, as I wrote custom Rector rules, I noticed AST power and flexibility and realized that full automation might be possible.
Also, FuelPHP is a relatively lightweight framework, and automated migration to Laravel, which has more features, was imaginable.

## FuelPHP to Laravel

**99% of the PHP files were converted automatically**, editing 200k+ lines of code.

An automated migration by Custom Rector rules of 2000+ files included:

* Fuel Query Builder → Laravel Query Builder
* Non psr-4 → psr-4
    * We created a dummy autoloader to run Rector, because we did not install FuelPHP
    * Adding namespaces, and moving files to the correct dir Converting Config
* `File, Response` Class → Laravel `Response` facades or helpers
* `Input, Upload` Class → Laravel `Request`  facades or helpers
* FuelPHP Exceptions → Mapped to Laravel Exceptions
* There were a lot of other ad-hoc rules specific to our code

<br>

A manual migration of ~20 files:

* Routes
    * There are no routes in FuelPHP
* Some parts of authentication
* Some parts of config
* FuelPHP specific classes.
    * ex. `Format`, `Agent`
    * wrote a custom facade in Laravel

Let's look into them in detail.

## Let's write a Rule to Migrate a Query Builder

Creating custom Rector rules to migrate the query builder was like creating a piece of a puzzle. We created **many small refactoring rules** and put the pieces together to modify the whole query.

For example, we wanted to convert FuelPHP...

```php
\DB::select_array(['id', 'name'])->from('user');
```

<br>

...to Laravel:

```php
\DB::table('user')->select_array(['id', 'name']);
```

<br>

For this refactoring, we created two rector rules.

1. Swap `from` and `select_array` and rename `from` to `table`
2. Convert `select_array` to `select`

<img src="/assets/images/blog/2022/query_builder_custom_rector_example.png" alt="" style="max-width: 25em" class="img-thumbnail">


### 1. Swap `from` and `select_array` and Rename `from` to `table`

The first rule can be written like this:

```php
public function getNodeTypes(): array
{
    return [MethodCall::class];
}

public function refactor(Node $fromNode): ?Node
{
    if (!$this->isName($fromNode->name, 'from')) {
        return null;
    }

    $selectNode = $fromNode->var;
    if (!$selectNode instanceof StaticCall ||
        $this->isNames($selectNode->name, ['select', 'select_array'])) {
        return null;
    }

    return new MethodCall(
        new StaticCall(
            new Node\Name\FullyQualified('DB'),
            new Node\Identifier('table'),
            $fromNode->args
        ),
        $selectNode->name,
        $selectNode->args
    );
}
```

Get method calls and check if the name is `from`. If the variable node of the method call is a static call of class `DB` named `select_array`, swap the static call and method call and rename the static call to “table”.

It's simple, isn't it?

### 2. Convert `select_array` to `select`

Then let's modify `select_array` to `select`. You need to expand the array to args and rename the method:

```php
public function getNodeTypes(): array
{
    return [MethodCall::class];
}

public function refactor(Node $selectArrayNode): ?Node
{
    if (!$this->isName($selectArrayNode->name, 'select_array')) {
        return null;
    }

    if (count($selectArrayNode->args) !== 1) {
        return null;
    }

    $array = $selectArrayNode->args[0]->value;
    if (!$array instanceof Node\Expr\Array_) {
        return null;
    }

    $selectArrayNode->name = new Node\Identifier('select');
    $selectArrayNode->args = array_map(
        fn(Node\Expr\ArrayItem $item) => new Node\Arg($item->value),
        $array->items
    );

    return $selectArrayNode;
}
```

Great! Now we can convert the whole query running these 2 rules.

## New Features and Migration at the Same Time?

This is the most significant and wonderful benefit of automated migration.
It's explained in detail in the [previous post](/blog/how-to-migrate-legacy-php-applications-without-stopping-development-of-new-features), so take a look if you haven't read it yet!

## What was Important for Automated migration?

### Tests

Migrating tests together with the application code and running them can be a critical indicator that the application works after applying Rector.
Sadly, our project did not have enough tests…

### PHPStan

It was another hero of the project besides Rector.
We created a baseline first and ran them after running Rector. We could find codes broken by running Rector and fix the Rector rules.

### Rector rule Tests

**Rector rule tests gave great confidence** that the modification in the migration itself is working.

We wrote about **80 Rector rules** to migrate the application, and the tests helped us find rules broken by dependencies and breaking changes of Rector's updates.

### Abstract Syntax Tree (AST)

A deep understanding of AST and Rector itself is essential to write custom Rector rules.

The most efficient way for me to learn them was to write the test fixtures of the Rector rules and dump them by nikic/php-parser. Trial and error writing rules and dumping the AST was an excellent way to understand the structure.

Also, I read a lot of codes of Rector, php-parser, PHPStan, and Larastan to understand how they are using, working with AST.

But as a shortcut, there is a [book about Rector](https://leanpub.com/rector-the-power-of-automated-refactoring) that explains AST and other vital things about Rectory. Let's read the Rector book!

## What have we Struggled with?

### Codes too Complicated to Convert by Rector

Sometimes some codes were too complicated to write a Rector rule. In these cases, we refactored the code itself to make it possible to convert by Rector or delete them if we could.

<blockquote class="blockquote text-center mt-5 mb-5">
    We deleted 100k+ lines of code during the migration!
</blockquote>

The important thing was that we were editing these codes in the "Development branch" to refactor and deploy the code in FuelPHP to confirm that the code was working before the migration release.

In some situations, writing custom rules is too tricky and expensive. We edited those in the migration branch and skipped automated migration for those files (about 10-20 files). It is essential to set a boundary, **what should be automated and what should be done manually**.

### Minor Differences between Frameworks

There were minor differences between frameworks, which were difficult to notice while writing custom rules.

For instance,

* FuelPHP return empty array response `response([])` with status code “204 No Contents” while Laravel does not
* FuelPHP `DB::insert` returns array of `['id', 'affected rows']` while Laravel `DB::insertGetId returns just 'id'`
* …etc.

For these differences, QA testing and canary release were crucial. We iterated testing over and over and fixed the custom rules to achieve the complete migration.

### Rector Bugs and Breaking Changes

We started the migration with Rector 0.9.x, and it's 0.12.x now! At 2020-2021, Rector was changing and evolving at a very high speed, and sometimes there were unstable versions with bugs. Also, some of our custom rules relied on Rector core codes, so there were significant breaking changes during the migration.

However, issues were already recognized by the community, and the fixes were extremely fast.

I very much appreciate the hard work of Tomas, other core developers, and the community of Rector!

## Pros and Cons

In brief,

Pros
* Works on large codebases
* Can decrease human errors of migration
* Could continue developing new features and run migration at the same time with no conflicts

Cons
* Converted code doesn't use the full functionality of Laravel
    * You can refactor them after migration!
* Requires understanding of AST
    * Let's read [the Rector book!](/book)

<br>

To be honest, I don't have any big cons for automated migration. It was a great experience, and I can say that we could not finish our migration without Rector.

Thank you!

<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
