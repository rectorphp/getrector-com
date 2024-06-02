---
id: 68
title: "Introducing Type Perfect to spot what PHPStan missed"
perex: |
    When dealing with legacy, we focus on safety by knowing the important types first. Code without type types cannot be refactored with Rector.

    Over time, we've been adding our custom rules to address PHPStan blind spots first. Today we're proud to publish them in one solid package.
---

These rules make skipped object types explicit, param types narrow and help you to fill more accurate object type hints. **It's easy to enable, even if your code doesn't pass level 0.**

They're easier to resolve, than getting your project to level 1. Their goal is to make your code instantly more solid and reliable. If you care about code quality and type safety, add these 10 rules to your CI.

## Low Hanging Fruit - Exact Instance

There are 2 checks enabled out of the box. First one makes sure we don't miss a chance to use `instanceof` to make further code know about exact object type:

```php
private ?SomeType $someType = null;

if (! empty($this->someType)) {
    // ...
}

if (! isset($this->someType)) {
    // ...
}

// here we only know, that $this->someType is not empty/null
```

🙅

↓


```php
if (! $this->someType instanceof SomeType) {
    return;
}

// here we know $this->someType is exactly SomeType
```

😊

<br>

Second rule checks we use explicit object methods over magic array access:

```php
$article = new Article();

$id = $article['id'];
// we have no idea, what the type is
```

🙅

↓

```php
$id = $article->getId();
// we know the type is int
```

😊

<br>

### Enable 3 Configured Groups

Next rules you can enable by configuration in `phpstan.neon`. We take them from the simplest to more powerful, in the same order we apply them on legacy projects.


## 1. Null over False

Enable in `phpstan.neon`:

```yaml
parameters:
    type_coverage:
        null_over_false: true
```

Bool types are typically used for on/off, yes/no responses. But sometimes, the `false` is missused as *no-result* response, where `null` would be more accurate:

```php
public function getProduct()
{
    if (...) {
        return $product;
    }

    return false;
}
```

🙅

↓

We should use `null` instead, as it enabled strict type declaration in form of `?Product` since PHP 7.1:

```diff
-public function getProduct()
+public function getProduct(): ?Product
 {
     if (...) {
         return $product;
     }

-    return false;
+    return null;
 }
```

😊

<br>

## 2. No mixed Caller

Enable in `phpstan.neon`:

```yaml
parameters:
    type_coverage:
        no_mixed: true
```

<br>

This group of rules focuses on PHPStan blind spot. If we have a property/method call with unknown type, PHPStan is not be able to analyse it. It silently ignores it.

```php
private $someType;

public function run()
{
    $this->someType->someMetho(1, 2);
}
```

It doesn't see there is a typo in `someMetho` name, and that the 2nd parameter must be `string`.

🙅

↓


```php
private SomeType $someType;

public function run()
{
    $this->someType->someMethod(1, 'active');
}
```

This group makes sure all property fetches and methods call know their type they're called on.

😊

<br>

## 3. Narrow Param Types

Last but not least, the narrowed param type declarations, the reliable the code.

Enable in `phpstan.neon`:

```yaml
parameters:
    type_coverage:
        narrow_param: true
```

<br>

In case of `private`, but also `public` method calls, our project knows exact types that are passed in it:

```php
// in one file
$product->addPrice(100.52);

// another file
$product->addPrice(52.05);
```

But from fear and "just to be safe", we keep the `addPrice()` param type empty, `mixed` or in a docblock.

🙅

↓

If in 100 % cases the `float` types is passed, PHPStan knows it can be added and improve further analysis:

```diff
-/**
- * @param float $price
- */
-public function addPrice($price)
+public function addPrice(float $price)
 {
     $this->price = $price;
 }
```

That's where this group comes in. It checks all the passed types, and let us know how to narrow the param type declaration.

😊

<br>

## Add Type Perfect to your project

First, make sure you have  [`phpstan/extension-installer`](https://github.com/phpstan/extension-installer#usage) to load necessary service configs. Then add the package:

```bash
composer require rector/type-perfect --dev
```

Run PHPStan to start improving your code. Add sets one by one on the go, fix what you find useful and ignore the rest.

<br>

Happy coding!
