---
id: 82
title: "Introducing Safe and Progressive Strict Type Adoption"
perex: |
    PHP's `declare(strict_types=1)` directive is a powerful tool for preventing subtle bugs.
    Yet most existing, mature projects don't use it consistently, if at all.
    Why? Because automatically adding it to all your files at once will cause your application to explode: expect thousands of errors.
    That leaves you fixing files by hand, one by one, and relying on team members to remember to add it to new files.
    Without a safe and automated process, adoption hardly ever sticks.

    Until now. The new `SafeDeclareStrictTypesRector` only adds strict types to files that are **already type-safe**, making safe, progressive adoption finally possible and preventing the file from becoming non-strict in the future.

author: calebdw
---

*This is a guest post by [Caleb White](https://x.com/calebdw), an engineer and open source contributor who has contributed to Rector, PHPStan, the Laravel framework, and many other projects.*

<br>

<div class="alert alert-warning mt-3 mb-5">
    This feature is still experimental. We look for your feedback in real projects.
    Did something break when it shouldn't have? Let us know <a href="https://github.com/rectorphp/rector/issues">on GitHub</a>.
</div>

## What is `strict_types` and Why Should You Care?

By default, PHP will coerce scalar values (`bool`, `float`, `int`, `string`, `null`) if there's a type mismatch.
For example, passing a `bool` to a function expecting a `string` will silently convert it to a string:

```php
<?php
function greet(string $name): string
{
    // do something
}
greet(true); // PHP silently converts to `"1"` with no warning or error
```

This "helpful" behaviour can mask serious bugs; in the example above, passing `true` to a method that expects a `string` name is likely a logic error that needs to be fixed but could go unnoticed.

[Strict typing](https://www.php.net/manual/en/language.types.declarations.php#language.types.declarations.strict) prevents this silent coercion by throwing a `TypeError` if the types do not match. To use, place `declare(strict_types=1)` at the top of your file and PHP will enforce exact type matching:

```php
<?php declare(strict_types=1);
function greet(string $name): string
{
    // do something
}
greet(true); // TypeError: must be of type string, bool given

// If you really need to coerce values, use casts to be explicit and signify intent
greet((string) $someBool);
```

Sure, some folks might argue against strict typing; that they're not a fan of having to be strict all the time and cast values to the correct type (if you're having to cast the same value back and forth multiple times, then you're likely doing something wrong).
But the reality is that strict typing **completely eliminates a certain class of bugs** that would otherwise go unnoticed and makes the type coercion **explicit**, **intentional**, and **visible**.

<br>

Here are some important things to remember about `strict_types`:

- it is only defined for scalar type **declarations**, meaning it is only enforced for:
  - arguments passed to functions, methods, callables, and constructors
  - return statements
  - property assignments
- the only exception is that an `int` value will pass a `float` type declaration
- it is **file-scoped**, meaning a file with `declare(strict_types=1)` can still `include` or `require` a file that is not strict
- it only affects **calling code**, so a function can be called non-strictly even if it is defined in a strict file

<br>

## A Real-World Horror Story

At my company, we learned this lesson the hard way.
Someone on our team was arguing against strict types and remarked, *"I've never had a bug that strict types would have prevented"*.
Less than a month later, the same individual refactored some code and accidentally introduced **exactly that kind of bug**.

💥 Our manufacturing numbers were suddenly wrong.

My manager and I spent two full days tracking it down.
No exceptions.
No errors or warnings in the logs.
The diff looked innocent enough, I reviewed the code from the last release multiple times before I finally found the culprit: **one method accepted an `int` instead of a `float`**. 😬
The refactor silently coerced a float length to an integer: e.g., `4.5` inches became `4` inches.
This cascaded through several calculations and ultimately wreaked havoc on our system.

If that one file had `declare(strict_types=1)`, we would have seen a `TypeError` immediately instead of burning four days of engineering time.

<div class="alert alert-warning">
    PHP has deprecated implicit float to integer type coercion since PHP 8.1 as it results in precision loss.
    However, these deprecation warnings can still be ignored or sent to a log file which might not be seen until it is too late.
</div>

<br>

## The Adoption Problem

If strict types are so valuable, why doesn't everyone use them? The answer is simple: **enabling strict types in an existing, mature codebase is painful**.

Existing tools like the `DeclareStrictTypesRector` or PHP-CS-Fixer's `declare_strict_types` rule simply add `declare(strict_types=1)` to every file *indiscriminately*: an all-or-nothing approach.
On a large codebase, this can introduce **thousands of `TypeError`s** instantly.

No team wants to:

- Fix tons of type errors across hundreds or thousands of files
- Risk introducing production bugs by changing behavior
- Spend weeks or months on a "refactoring" that stakeholders see as delivering zero business value

So strict types adoption stalls.
Teams know they should use it, but the path to get there seems impossible.
And even with the best intentions, teams are rarely unified: some developers champion strict types, others are indifferent, and everyone occasionally forgets.
Relying on manual discipline across a whole team just doesn't scale.

<br>

## Enter `SafeDeclareStrictTypesRector`

The new `SafeDeclareStrictTypesRector` takes a fundamentally different approach: **it only adds `declare(strict_types=1)` to files that are already type-safe**.

Instead of blindly adding the declaration everywhere and breaking things, it analyzes each file to ensure that enabling strict types won't change any runtime behavior.
If a file would throw a `TypeError` after adding strict types, Rector skips it entirely.

### How It Works

For every file, the rule examines every place where type declaration coercion could occur:

1. **Function/method/callable/constructor calls**: are all arguments compatible with parameter types?
2. **Return statements**: are all returned values compatible with the declared return type?
3. **Property assignments**: are all assigned values compatible with typed properties?

For each of these, it uses PHPStan's type inference to check whether the actual value types are strictly compatible with the declared types.
Only if **every** type coercion point passes does Rector add the declaration.

```php
<?php
// This file gets strict_types added because it's already safe
function greet(string $name): string
{
    // do something
}
echo greet("World"); // String passed to string parameter: safe!
```

```php
<?php
// This file is SKIPPED because it relies on type coercion
function setQuantity(int $quantity): void
{
    // do something
}
setQuantity("3"); // String passed to int parameter: unsafe!
```

<br>

### Conservative by Design

The checker is intentionally conservative. If it can't be 100% certain a file is safe, it skips it:

- Dynamic method calls where the reflection can't be resolved? **Skip.**
- Argument unpacking with `...$var`? **Skip.**
- Mixed types that could be anything? **Skip.**

This means you might not get strict types added on every file that *could* have it, but **you'll never wind up with a broken file**.

<br>

## Progressive Adoption Made Possible

The real power of `SafeDeclareStrictTypesRector` is that it enables **progressive adoption**:

1. Run Rector: safe files get `declare(strict_types=1)` automatically
2. Those files are now protected from future type coercion bugs
3. PHPStan will catch any new violations, preventing those files from becoming non-strict again
4. Fix remaining files incrementally as time permits
5. Re-run Rector periodically to catch newly-safe files (preferably in a CI pipeline)

This rule also works in conjunction with other Rector rules.
Rector has rules that add parameter types, return types, and even cast arguments to the correct type.
As these rules run, files that were previously unsafe become safe.
Once a file is fully type-safe, `SafeDeclareStrictTypesRector` will automatically add the declaration.

This creates a virtuous cycle: Rector adds types and casts, files become strict-safe, and PHPStan prevents regressions.
Your codebase gradually becomes more type-safe over time with minimal manual effort.

In our enterprise application, the first run added `declare(strict_types=1)` to **over 1,500 files**: completely automatic and with zero risk of breakage.
That's 1,500 files now protected from the exact class of bug that cost us two days of debugging. 💪

<br>

## Try It Yourself

Add the rule to your Rector configuration:

```diff
<?php
use Rector\Config\RectorConfig;
+use Rector\TypeDeclaration\Rector\StmtsAwareInterface\SafeDeclareStrictTypesRector;

return RectorConfig::configure()
    ->withRules([
+        SafeDeclareStrictTypesRector::class,
    ]);
```

Then run Rector: watch as safe files automatically get `declare(strict_types=1)` while unsafe files are left untouched.
No breakage. No risk. Just automatic improvement.

<br>

## Conclusion

Strict types shouldn't be an all-or-nothing choice.
With `SafeDeclareStrictTypesRector`, you can adopt `declare(strict_types=1)` progressively, automatically, and safely.

Every file that gets strict types added is one more file protected from silent type coercion bugs.
Those bugs might cost you two days of debugging like they did for us, or they might cost you much more.

Start protecting your codebase today. 🛡️
