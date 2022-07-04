---
id: 37
title: "How to Automatically Add Return Type Declarations without Breaking Your Code"
perex: |
    Code filled with docblocks param, var, and return types is a gold mine. Not in the meaning of valuable resource, but rather as exploding metal covered with a thin piece of gold, so we grab it without thinking. While these docblocks give us much information about the code, they might be nothing more than a wish, dream, or promise.
    <br><br>
    Have you ever blindly trusted docblocks and switched them to type declarations? Then you know the explosive regression this move brings.
    <br><br>
    Yet, how can we turn to add strict types to our code without fear of breaking it?
---

## Docblock Trust is Blind

What can we read from this code if we trust it?

```php
class SweetPromise
{
    private $values;

    /**
     * @param mixed[] $values
     */
    public function setValues($values)
    {
        $this->values = $values;
    }

    /**
     * @return string[]
     */
    public function getValues()
    {
        return $this->values;
    }
}
```

The values are always an `array` of strings. Let's tolerate strings and work with the `array` type.

<br>

If that is true, these should not be possible:

```php
$sweetPromise = new SweetPromise();
echo $sweetPromise->getValues(); // null or array?

$sweetPromise->setValues('{55}'); // string or array?
echo $sweetPromise->getValues(); // string or array?
```

We have no idea about the value we put in. We cannot make any assumptions unless we're ready to risk the type failure and manual verification of every single type.

<br>

The honest filter would show the class above like this:

```php
class SweetPromise
{
    private $values;

    public function setValues($values)
    {
        $this->values = $values;
    }

    public function getValues()
    {
        return $this->values;
    }
}
```

<br>

Now it's obvious **we can't trust the docblocks**. Mainly because the docblock types are not based on actual code but ideal state = if every method is 100 % reliable, called in precisely defined order, and with 100 % reliable typed input variables. Which they're not.

<br>

## Millions of Lines with Docblocks

This sound a little depressing. So what can we do if we have millions of lines of code with docblocks? We want **to move fast and safe at the same time**.

We have a few options:

* go line by line, try to detect the pattern, and add the first single, strict type declaration
* then [propagate this type](/blog/2021/02/15/how-much-does-single-type-declaration-know) to all calls of this code
* risk the automated docblock conversion and let the user do the testing; 500 on the server means the type was not detected correctly
* go with certain types

<br>

## What are "Certain Types"?

Certain types are based on actual values, operations, and logical structures that **have under any circumstances always exactly one type**:

* exact scalar value
    * `5` → `int`
    * `"hi"` → `string`
* values based on PHP internal functions
    * [`strlen()` always returns `int`](https://www.php.net/manual/en/function.strlen.php)
    * [`substr()` always returns `string`](https://www.php.net/manual/en/function.substr.php)
* result of binary operators
    * `$anything && $somethingElse` always returns `bool`
    * `100 - 20` always returns `int`
    * `! $anything` always returns `bool`

<br>

Not only one-line values but also more complex structures like created and filled array:

```php
function provideDreams()
{
    $dreams = [];

    foreach ($this->dreamRepository->fetchAll() as $dream) {
        $dreams[] = $dream;
    }

    return $dreams;
}
```

This code always returns `array`, no matter what `$dream` actually is.

<br>

Based on these 4 rules, we can complete the following types with 100 % certainty:

```diff
 final class Reality
 {
-    public function getAge()
+    public function getAge(): int
     {
         return 100;
     }

-    public function removeTax(int $value)
+    public function removeTax(int $value): int
     {
         return $value - 200;
     }

-    public function transform($value)
+    public function transform($value): string
     {
         if ($value === null) {
             return '';
         }

         return base64_encode($value);
    }
}
```

And much more!

<br>

Depending on what age your project is coming from, there is one requirement to make this work. You must use PHP 7.0, where [return type declarations](https://www.php.net/manual/en/migration70.new-features.php#migration70.new-features.return-type-declarations) were added.

## Try it Yourself

For each case above, there is one rector rule that handles this case. Add these 4 rules to your code and see for yourself:

```php
use Rector\Config\RectorConfig;
use Rector\CodeQuality\Rector\ClassMethod\ReturnTypeFromStrictScalarReturnExprRector;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictBoolReturnExprRector;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNativeFuncCallRector;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNewArrayRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rules([
        ReturnTypeFromStrictBoolReturnExprRector::class,
        ReturnTypeFromStrictNativeFuncCallRector::class,
        ReturnTypeFromStrictNewArrayRector::class,
        ReturnTypeFromStrictScalarReturnExprRector::class,
    ]);
};
```

Run Rector and see how many new types were added. In the project we tested these rules on, we had over 40 changed files in the first run. From zero to 40 files, quite impressive. And as you know, strict types [spread exponentially](/blog/2021/02/15/how-much-does-single-type-declaration-know).

<br>

What other cases where we are 100 % certain could you think of?
Let us know in the comments.
