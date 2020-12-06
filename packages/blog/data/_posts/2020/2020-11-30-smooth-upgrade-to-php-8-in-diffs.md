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

## tl;dr; in 5 minutes

```bash
composer require rector/rector --dev
vendor/bin/rector init
```

Update `rector.php` with PHP 8 set:

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

Run Rector:

```bash
vendor/bin/rector process src
```

<br>

How does such upgrade look in practise? See one of real pull-requests created with Rector:

- [tomasvotruba.com](https://github.com/TomasVotruba/tomasvotruba.com/pull/1107/files)
- [getrector.org](https://github.com/rectorphp/getrector.org/pull/190/files)
- [friendsofphp.org](https://github.com/TomasVotruba/friendsofphp.org/pull/176/files)

### Smooth Upgrade?

The goal of this tutorial is to prepare your for expected required steps, so the upgrade will require the least effort possible. Follow the guide and get to PHP 8 like a walk in the park.

## What will Rector handle for You?

### 1. From `switch()` to `match()`

```diff
-$statement = switch ($this->lexer->lookahead['type']) {
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

### 2. From `get_class()` to Faster `X::class`

```diff
-get_class($object);
+$object::class;
```

### 3. From Dummy Constructor to Promoted Properties

```diff
 class SomeClass
 {
-    public float $alcoholLimit;
-
     public function __construct(
-        float $alcoholLimit = 0.0,
-    ) {
-        $this->alcoholLimit = $alcoholLimit;
-    }
+        public float $alcoholLimit = 0.0,
+    ) {}
 }
```

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

### 6. Unused Catched $variable is not Needed Anymore

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

### 7. New `str_contains()` Function

```diff
-$hasA = strpos('abc', 'a') !== false;
+$hasA = str_contains('abc', 'a');
```

### 8. New `str_ends_with()` Function

```diff
-$isMatch = substr($haystack, -strlen($needle)) === $needle;
+$isMatch = str_ends_with($haystack, $needle);
```

### 9. New `str_starts_with()` Function

```diff
-$isMatch = substr($haystack, 0, strlen($needle)) === $needle;
+$isMatch = str_starts_with($haystack, $needle);
```

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

This class can now be used in places, where `string` type is needed:

```php
function run(string $anyString)
{
   // ...
}

$name = new Name('Kenny');
run($name);
```

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

### 12. Symfony Annotations to Attributes

```diff
 use Symfony\Component\Routing\Annotation\Route;

 class SomeController
 {
-   /**
-    * @Route(path="blog/{postSlug}", name="post", requirements={"postSlug"=".+"})
-    */
+    #[Route('blog/{postSlug}', name: 'post', requirements: [
+        'postSlug' => '.+',
+    ])]
     public function __invoke(): Response
     {
         // ...
     }
 }
```

### 13. From Doctrine Annotations Markers to Attribute

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

Then use in code with attribute syntax:

```diff
 final class DemoFormData
 {
-    /**
-     * @PHPConstraint()
-     */
-    private string $content;
-
-    /**
-     * @PHPConstraint()
-     */
-    private string $config;

-    public function __construct(string $content, string $config)
-    {
-        $this->content = $content;
-        $this->config = $config;
+    public function __construct(
+        #[PHPConstraint]
+        private string $content,
+        #[PHPConstraint]
+        private string $config,
+    ) {
     }
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

<br>

Do you have problems with `composer install`?

## How to Allow PHP 8 Blocking Packages

Some packages didn't get to update their `composer.json`, so be nice and help them and all their users with small pull-request:

```diff
 {
     "require": {
-        "php": "^7.3"
+        "php": "^7.3|^8.0"
     }
 }
```

Other packages are blocking PHP 8 upgrade from own their maintainers' ideology, even though the code runs on PHP 8 with no problems. There is great [15-min video](https://www.youtube.com/watch?v=c3bpTBjhK2Y) about this topic by Nikita Popov, the most active PHP core contributor, and Nikolas Grekas, the main Symfony contributor.

But ideology is not why we're here. **We want to upgrade our project to PHP 8**. Thanks to Composer 2, this can [be easily solved](https://php.watch/articles/composer-ignore-platform-req):

```diff
-composer install
+composer upgrade --ignore-platform-req php
```

Upgrade your CI workflows and Docker build scripts and you're ready to go.

<br>

Happy coding!
