---
id: 7
title: "Smooth Upgrade to PHP 8 in Diffs"
perex: |
    PHP 8 was released more than 2 weeks ago. Do you want to know what is new? Check [colorful post series about PHP 8 news](https://stitcher.io/blog/new-in-php-8) by Brent.
    <br>
    <br>
    Do you want to upgrade your project today?

    Continue reading ↓
---

## In a Rush to Private Jet?

1. Do it in 5 minutes:

```bash
composer require rector/rector --dev
# create "rector.php"
vendor/bin/rector init
```

2. Update `rector.php` with PHP 8 set:

```diff
 use Rector\Core\Configuration\Option;
+use Rector\Set\ValueObject\SetList;
 use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

 return static function (ContainerConfigurator $containerConfigurator): void {
     $parameters = $containerConfigurator->parameters();

     $parameters->set(Option::SETS, [
+        SetList::PHP_80,
     ]);
 };
```

3. Run Rector:

```bash
vendor/bin/rector process src
```

<br>

How does such upgrade look in practise? See one of real pull-requests created with Rector:

- [tomasvotruba.com](https://github.com/TomasVotruba/tomasvotruba.com/pull/1107/files)
- [getrector.org](https://github.com/rectorphp/getrector.org/pull/190/files)
- [friendsofphp.org](https://github.com/TomasVotruba/friendsofphp.org/pull/176/files)

### Smooth Upgrade?

This tutorial aims to prepare you for the expected required steps so that the upgrade will require the least effort possible. Follow the guide and get to PHP 8 like **a walk in the park**.

## What Rector handles for You?

### 1. From `switch()` to `match()`

```diff
-switch ($this->lexer->lookahead['type']) {
-    case Lexer::T_SELECT:
-        $statement = $this->SelectStatement();
-        break;
-
-    default:
-        $this->syntaxError('SELECT, UPDATE or DELETE');
-        break;
-}
+$statement = match ($this->lexer->lookahead['type']) {
+    Lexer::T_SELECT => $this->SelectStatement(),
+    default => $this->syntaxError('SELECT, UPDATE or DELETE'),
+};
```

<br>

### 2. From `get_class()` to Faster `X::class`

```diff
-get_class($object);
+$object::class;
```

<br>

### 3. From Dummy Constructor to Promoted Properties

```diff
 class SomeClass
 {
-    public float $alcoholLimit;
-
-    public function __construct(float $alcoholLimit = 0.0)
+    public function __construct(public float $alcoholLimit = 0.0)
     {
-        $this->alcoholLimit = $alcoholLimit;
     }
 }
```

<br>

### 4. Private Final Methods are Not Allowed Anymore

```diff
 class SomeClass
 {
-    final private function getter()
+    private function getter()
     {
         return $this;
     }
 }
```

<br>

### 5. Replace Null Checks with Null Safe Calls

```diff
 class SomeClass
 {
     public function run($someObject)
     {
-        $someObject2 = $someObject->mayFail1();
-        if ($someObject2 === null) {
-            return null;
-        }
-
-        return $someObject2->mayFail2();
+        return $someObject->mayFail1()?->mayFail2();
     }
 }
```

<br>

### 6. Unused $variable in `catch()` is not Needed Anymore

```diff
 final class SomeClass
 {
     public function run()
     {
         try {
-        } catch (Throwable $notUsedThrowable) {
+        } catch (Throwable) {
         }
     }
 }
```

<br>

### 7. New `str_contains()` Function

```diff
-$hasA = strpos('abc', 'a') !== false;
+$hasA = str_contains('abc', 'a');
```

<br>

### 8. New `str_starts_with()` Function

```diff
-$isMatch = substr($haystack, 0, strlen($needle)) === $needle;
+$isMatch = str_starts_with($haystack, $needle);
```

<br>

### 9. New `str_ends_with()` Function

```diff
-$isMatch = substr($haystack, -strlen($needle)) === $needle;
+$isMatch = str_ends_with($haystack, $needle);
```

<br>

### 10. New `Stringable` Interface for

```diff
-class Name
+class Name implements Stringable
 {
-    public function __toString()
+    public function __toString(): string
     {
         return 'I can stringz';
     }
 }
```

Class that implements `Stringable` can now be used in places, where `string` type is needed:

```php
function run(string $anyString)
{
   // ...
}

$name = new Name('Kenny');
run($name);
```

<br>

### 11. From Union docblock types to Union PHP Declarations

```diff
 class SomeClass
 {
-    /**
-     * @param array|int $number
-     * @return bool|float
-     */
-    public function go($number)
+    public function go(array|int $number): bool|float
     {
     }
 }
```

<br>

### 12. Symfony Annotations to Attributes

```diff
 use Symfony\Component\Routing\Annotation\Route;

 class SomeController
 {
-   /**
-    * @Route(path="blog/{postSlug}", name="post")
-    */
+    #[Route('blog/{postSlug}', name: 'post')]
     public function __invoke(): Response
     {
         // ...
     }
 }
```

<br>

### 13. From Doctrine Annotations to Attributes

```diff
-use Doctrine\Common\Annotations\Annotation\Target;
+use Attribute;
 use Symfony\Component\Validator\Constraint;

-/**
- * @Annotation
- * @Target({"PROPERTY", "ANNOTATION"})
- */
+#[Attribute(Attribute::TARGET_PROPERTY)]
 final class PHPConstraint extends Constraint
 {
 }
```

Then use in code with attributes:

```diff
 final class DemoFormData
 {
-    /**
-     * @PHPConstraint()
-     */
+    #[PHPConstraint]
     private string $content;
-
-    /**
-     * @PHPConstraint()
-     */
+    #[PHPConstraint]
     private string $config;

    // ...
```

<br>

Don't bother with any of the steps above. Let Rector handle it.

## Update Dockerfile

Do you use Docker? Upgrade images to new PHP version:

```diff
 ####
 ## Base stage, to empower cache
 ####
-FROM php:7.4-apache as base
+FROM php:8.0-apache as base
```

## GitHub Actions

Update `shivammathur/setup-php@v2` in your workflows:

```diff
 jobs:
     unit_tests:
         runs-on: ubuntu-latest
         steps:
             -   uses: actions/checkout@v2
             -   uses: shivammathur/setup-php@v2
                 with:
+                    php-version: 7.4
-                    php-version: 8.0
```

## Skip incompatible Coding Standard rules

These 3 rules are not compatible with PHP 8 yet. So better skip them in `ecs.php`:

- `PhpCsFixer\Fixer\ClassNotation\ClassAttributesSeparationFixer`
- `PhpCsFixer\Fixer\Operator\BinaryOperatorSpacesFixer`
- `SlevomatCodingStandard\Sniffs\Classes\DisallowMultiPropertyDefinitionSniff`

## Do you Have Conflicts With `composer install`?

Some packages didn't get to update their `composer.json`. **Be nice and help your fellow developers** with a small pull-request:

```diff
 {
     "require": {
-        "php": "^7.3"
+        "php": "^7.3|^8.0"
     }
 }
```

Other packages block PHP 8 upgrades from their own maintainers' ideology, **even though the code runs on PHP 8**. Watch **an excellent [15-min video](https://www.youtube.com/watch?v=c3bpTBjhK2Y)** about this by [Nikita Popov](https://twitter.com/nikita_ppv), the most active PHP core developer, and [Nikolas Grekas](https://twitter.com/nicolasgrekas), the same just for Symfony.

But ideology is not why we're here. **We want to upgrade our project to PHP 8**. Thanks to Composer 2, this can [be easily solved](https://php.watch/articles/composer-ignore-platform-req):

```diff
-composer install
+composer upgrade --ignore-platform-req php
```

Upgrade your CI workflows and Docker build scripts, and you're ready to go.

<br>

Happy coding!
