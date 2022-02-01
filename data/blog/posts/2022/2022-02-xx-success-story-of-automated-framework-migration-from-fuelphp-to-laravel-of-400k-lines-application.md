# Success Story of Automated Framework Migration from FuelPHP to Laravel of 400k+lines Application

<blockquote class="twitter-tweet"><p lang="en" dir="ltr">For some framework switch is impossible. <br>Others can do it with zero downtime 👏 <a href="https://t.co/votwydILzX">https://t.co/votwydILzX</a></p>&mdash; Rector (@rectorphp) <a href="https://twitter.com/rectorphp/status/1479052818422116352?ref_src=twsrc%5Etfw">January 6, 2022</a></blockquote> <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>

Today, I’m very excited to talk about the full story of our successful automated framework migration, how Rector saved our product by refactoring our 400k+lines PHP web application!

## Summary of the migration project

Our application was a monolithic application consisting of backend of web service, native App API, intra company support tools and batch jobs for web and app.

* Framework
  * FuelPHP -> Laravel
* PHP version 
  * PHP7.0 -> PHP7.4
* Application 
  * Released in 2015/11
  * 2000+ PHP files, 400k+ lines of PHP codes
* Schedule 
  * 2021/01 ~ 2021/11 
  * Migrated intracompany tools at 2021/09 (QA from
2021/07 ~)
  * Start canary release of Web and API from 2021/11/1 (QA from 2021/08 ~)
  * Switched to Laravel with 100% release at 2021/11/16 
* Members 
  * 1~2 engineers + 1 senior engineer for advices
* Special notes
  * Running the migration and developing new features at the same time 
  * Zero downtime by releasing old and new environment in canary release

## Why did we choose Rector?

Why did we choose Rector? Why did we decide to automate the migration? The reason was simple. 

Our application was too large to migrate manually, and some kind of automation was definitely needed to succeed the migration. 
Even more, we estimated that it might take about a year for migration without automation, even if all team members worked for migration. 

**If it's the same speed with human work, why not try something new that might be faster?**

## Fully automated migration

At first, we were using Rector to only convert DB query builders of FuelPHP to Laravel, and manually modifying controllers, configs, “Facades” (≒ “Classes” in FuelPHP). 
However, as I wrote custom rules Rector rules, I noticed the power and the flexibility of AST, and realized that full automation might be possible. 
Also, FuelPHP is a rather lightweight framework, and automated migration to Laravel, which has more features, was imaginable.

## FuelPHP to Laravel

99% of the PHP files were converted automatically, editing 200k+ lines of code.

* Automated migration by Custom Rector rules (2000+ files)
  * Fuel Query Builder -> Laravel Query Builder 
  * Non psr-4 -> psr-4 
    * We created a dummy autoloader to run Rector, because we did not install FuelPHP
    * Adding namespaces, and moving files to the correct dir Converting Config
  * `File, Response` Class -> Laravel `Response` facades or helpers
  * `Input, Upload` Class -> Laravel `Request`  facades or helpers 
  * FuelPHP Exceptions -> Mapped to Laravel Exceptions 
  * There were a lot of other ad-hoc rules specific to our code

* Manual migration (~20 files)
  * Routes 
    * There are no routes in FuelPHP 
  * Some parts of authentication 
  * Some parts of config 
  * FuelPHP specific classes. 
    * ex. `Format`, `Agent` 
    * wrote a custom facade in Laravel

Let’s look into them in detail.

### Query Builder custom Rector rule example

Creating custom Rector rules to migrate the query builder was like creating a piece of puzzle. We created a bunch of small refactoring rules and put the pieces together to modify the whole query.

For example, we wanted to convert

FuelPHP

```php
\DB::select_array(['id', 'name'])->from('user');
```

to

Laravel

```php
\DB::table('user')->select_array(['id', 'name']);
```

For this refactoring, we created two rector rules.

1. Swap `from` and `select_array` and rename `from` to `table`
2. Convert `select_array` to `select`

<img src="/assets/images/blog/2022/query_builder_custom_rector_example.png" alt="" style="max-width: 35em" class="img-thumbnail">


### Swap `from` and `select_array` and rename `from` to `table`

The first rule can be written like this.

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

### Convert `select_array` to `select`

Then let’s modify `select_array` to `select`. You need to expand the array to args, and rename the method.

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

Great! Now we can convert the whole query running these two rules.

Rector can perform much more flexible refactoring. For example, sometimes the array arg of the `select_array` method is passed by variables.

```php
select_array($array)
```

Then we can convert them like this

```php
select(...$array)
```

Just add a little code to handle that case.

```php
public function refactor(Node $selectArrayNode): ?Node
{
    if (!$this->isName($selectArrayNode->name, 'select_array')) {
        return null;
    }

    if (count($selectArrayNode->args) !== 1) {
        return null;
    }

    $value = $selectArrayNode->args[0]->value;
    if ($value instanceof Node\Expr\Array_) {
        $selectArrayNode->args = array_map(
            fn(Node\Expr\ArrayItem $item) => new Node\Arg($item->value),
            $value->items
        );
    } else if ($value instanceof Node\Expr\Variable) {
        $selectArrayNode->args[0]->unpack = true;
    } else {
        return null;
    }

    $selectArrayNode->name = new Node\Identifier('select');
    return $selectArrayNode;
}
```

## Developing new features and running migration at same time

This was the most significant and wonderful benefit of automated migration. 
It’s explained in detail in the [previous post](todo), so please take a look if you haven’t read it yet!

## What was Important for Automated migration?

### Tests

Migrating tests together with the application code and running them can be one important indicator that the application works after applying Rector. 
Sadly, our project did not have enough tests…

### PHPStan

It was another hero of the project besides Rector. 
We created a baseline first and ran them after running Rector. We could find codes broken by running Rector and fix the Rector rules.

### Rector rule tests

Rector rule tests gave great confidence that the modification in the migration itself is working. 
We wrote about 80 Rector rules to migrate the application, and the tests helped us find rules broken by dependencies, and also breaking changes of Rector’s updates.

### AST

Deep understanding in AST and Rector itself, was very important to write custom Rector rules. 

The most efficient way for me to learn them was to write the test fixtures of the Rector rules and dump them by nikic/php-ast. 
Trial and error writing rules and dumping the AST was a very good way to understand the structure. 
Also, I read a lot of codes of Rector, php-ast, PHPStan, larastan to understand how they are using, working with AST. 

But as a shortcut, there is a [book about Rector](https://leanpub.com/rector-the-power-of-automated-refactoring) that explains AST and other important things about Rectory. Let's read the Rector book!

## Things struggled during Automated migration

### Codes too complicated to convert by Rector

Sometimes there were codes that were too complicated to write a Rector rule. In these cases we refactored the code itself to make it possible to convert by Rector, or simply delete them if we could. We deleted 100k+ lines of code during the migration!
The important thing was that we were editing these codes in the "Development branch", so we could refactor and deploy the code in FuelPHP to confirm that the code is working before the migration release.

For cases writing custom rules is too difficult/high cost, we edited them in the migration branch and skipped automated migration for those files (about 10 ~ 20 files). It is important to set a boundary, what should be automated and what should not.

### Minor differences between frameworks

There were minor differences between frameworks, difficult to notice while writing custom rules at first. 

For instance, 

* FuelPHP return empty array response `response([])` with status code “204 No Contents” while Laravel does not
* FuelPHP `DB::insert` returns array of `['id', 'affected rows']` while Laravel `DB::insertGetId returns just 'id'`
* …etc. 

For these differences, QA testing and canary release was very important. We iterated over and over testing and fixing the custom rules to achieve the complete migration.

### Rector bugs and breaking changes

We started the migration with Rector 0.9.x and it’s 0.12.x now! At 2020-2021, Rector was changing and evolving at a very high speed, and sometimes there were unstable versions with bugs. Also, some of our custom rules were relying on Rector core codes, so there were big breaking changes during the migration. 

However, most times issues were already recognized by the community, and the fixes were extremely fast. 

I very appreciate the hard work of Tomas, other core developers and the community of Rector!

## Pros and Cons

In brief,

Pros 
  * Works on large code bases 
  * Can decrease human errors of migration 
  * Could continue developing new features and run migration at the same time with no conflicts 

Cons 
  * Converted code doesn’t use the full functionality of Laravel
    * You can refactor them after migration!
  * Requires understanding of AST 
    * Let’s read the Rector book!

To be honest, I don’t have any big cons for automated migration. It was a great experience, and I can definitely say that we could not finish our migration without Rector. Thank you!
