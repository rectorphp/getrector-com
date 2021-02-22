---
id: 18
title: "How much does Single Type Declaration Know?"
perex: |
    When it comes to completing type declaration from docblocks, we rely on trust and hopes in commented code. One way out of is [dynamic analysis](https://tomasvotruba.com/blog/2019/11/11/from-0-doc-types-to-full-type-declaration-with-dynamic-analysis/) that works with real data that enter the method. But we have to log it, wait for it, and update our codebase based on logged data.
    <br><br>
    **Is there a faster, simpler solution we can just plugin?**
---

Let's say we have a `Person` object:

```php
final class Person
{
    /**
     * @var string
     */
    public $name;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
```

How sure are you about the name being a `string`? 80-95 %? Every percent under 100 % is a probability of a bug behind the corner.

We can do this:

```php
$person = new Person(1000);
```

Or even this (don't try to imagine it):

```php
$person = new Person(1000);
$anotherPerson = new Person($person);
```

See [3v4l.org](https://3v4l.org/Of6KU).

Rector has a `TYPE_DECLARATION` set to autocomplete types based PHPStan types that rely heavily on docblocks even in strict mode. This set is useful for keeping high code quality but might break some older code.

## Single Type Declaration

The way out of legacy is to completely type declaration right in PHP to every single place it can appear:

- param types
- return types
- property types

Such work has enormous benefits, as we can rely 100 % on the types and move our focus on more critical parts. But **it is tedious and prolonged work**.

When it comes to a single type of declaration, there is more than meets the eye. More encoded knowledge is not visible to the human eye, but it is there.

Let's say we add a single type we are sure off:

```diff
 final class Person
 {
     /**
      * @var string
      */
     public $name;

-    /**
-     * @param string $name
-     */
-    public function __construct($name)
+    public function __construct(string $name)
     {
         $this->name = $name;
     }

     /**
      * @return string
      */
     public function getName()
     {
         return $this->name;
     }
}
```


## Causality

The param `$name` in a constructor is always a string. What does it mean for the rest of the code? Assign in the constructor to property means that property uses identical type:

```diff
 final class Person
 {
-    /**
-     * @var string
-     */
-    public $name;
+    public string $name;

     public function __construct(string $name)
     {
         $this->name = $name;
     }

     /**
      * @return string
      */
     public function getName()
     {
         return $this->name;
     }
}
```

<br>

The `$name` property is now the `string` type right from the object construction. In effect, any getter inherits the same type:

```diff
 class Person
 {
     public string $name;

     public function __construct(string $name)
     {
         $this->name = $name;
     }

-    /**
-     * @return string
-     */
-    public function getName()
+    public function getName(): string
     {
         return $this->name;
     }
}
```

Now we have an object fully typed, and all we had to do is **complete a single type in constructor**.

What about places that are using the `Person` object?

```diff
 final class PersonScanner
 {
-    public function getPersonName(Person $person)
+    public function getPersonName(Person $person): string
     {
         return $person->getName();
     }
 }
```

And all methods using `PersonScanner->getPersonName()`? They know the  `string` too. This healthy immunity is now spreading through our code base with every single type of declaration we add.

From **single manually added type declaration** Rector can autocomplete:

- property type
- getter return type
- getter base on method call return type
- every method call in the chain using typed property or getter return type

Rector watch will save you so much detailed detective work on types that are already in the code but hard to spot.

## Try it Yourself

Add [`TYPE_DECLARATION_STICT` set](https://github.com/rectorphp/rector/blob/master/config/set/type-declaration-strict.php) yourself of pick rule by rule, so you can see how your code base becomes strict for each new rule you add:

```php
// rector.php
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Rector\TypeDeclaration\Rector\ClassMethod\ParamTypeFromStrictTypedPropertyRector;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnNewRector;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedPropertyRector;
use Rector\TypeDeclaration\Rector\Property\TypedPropertyFromStrictConstructorRector;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(ParamTypeFromStrictTypedPropertyRector::class);
    $services->set(ReturnTypeFromReturnNewRector::class);
    $services->set(ReturnTypeFromStrictTypedPropertyRector::class);
    $services->set(ReturnTypeFromStrictTypedCallRector::class);
    $services->set(TypedPropertyFromStrictConstructorRector::class);
};
```

<br>

Happy coding!
