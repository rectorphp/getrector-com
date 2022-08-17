---
id: 21
title: "From Doctrine Annotations Parser to Static Reflection"
perex: |
    Until recently, we used doctrine/annotations to parse class annotations that you know `@ORM\Entity` or `@Route`. Last 2 weeks, we **rewrote this parser from scratch to our custom solution** to improve spaces, constants and use static reflection.
    <br><br>
    During refactoring, the parser got **reduced from 6700 lines to just 2700**.
    <br>
    What we changed, why, and how can we benefit from a static reflection in annotations?

since_rector: 0.10
---

The [doctrine/annotations](https://github.com/doctrine/annotations) package has been an excellent help for Rector for the past couple of years. Symfony, Doctrine, JMS, or Gedmo use the same package. We used it to parse the following code to a custom value object:

```php
use Doctrine\ORM\Mapping as ORM;

// ...

/**
 * @ORM\Column(type="text")
 */
private $config;
```

Here the object was `Rector\Doctrine\PhpDoc\Node\Property_\ColumnTagValueNode`. This object provided data about all inner values:

```php
$columnTagValueNode->getType(); // "text"
```

That way, we could modify the content, get the value type to add `@var string`  etc. Straightforward object API with method IDE auto-complete. So far, so good?

<br>

## Ups and Downs of Doctrine Annotations

The problem was that for every such annotation, we had to have a custom object. That means **lot of classes**:

<img src="https://user-images.githubusercontent.com/924196/113852905-58c69100-979d-11eb-9fe8-b2db8c406c02.png" class="img-thumbnail">

Also, each class had its factory service that mapped annotation class to our custom `*TagValueNode`. Phew.

<br>

## No Static Reflection

Doctrine parser uses `class_exists()` and native reflection to load the `Column` class annotation properties:

<img src="https://user-images.githubusercontent.com/924196/113853843-70eae000-979e-11eb-96e4-6241b4c32d64.png" class="img-thumbnail pull-left">

<img src="https://user-images.githubusercontent.com/924196/113854892-b0fe9280-979f-11eb-837f-940ff33e593f.png" class="img-thumbnail ml-4">


<div class="clearfix"></div>

That means the [static reflection we added in Rector 0.10](/blog/2021/03/15/legacy-refactoring-made-easy-with-static-reflection) cannot be used here. That means you have to include the annotation classes in your autoloader. It's very confusing.

<br>

## Constants are Replaced by their Values

The Doctrine parser is used only for reading the values. In Rector, we need to print the docblock back, e.g., change the type from "text" to "number".

That worked most of the time, but what if there was a constant? The **constants are replaced by their values** [right here](https://github.com/doctrine/annotations/blob/c66f06b7c83e9a2a7523351a9d5a4b55f885e574/lib/Doctrine/Common/Annotations/DocParser.php#L1155).

This causes bugs like these:

```diff
public const VALUES = [
    '4star' => FiveStar::class,
];

 /**
- * @Assert\Choice(choices=self::VALUES)
+ * @Assert\Choice({"4star":"App\Entity\Rating\FourStar"})
  */
```

Instead, we need to keep the original value of "self::VALUES", a bare constant reference. To overcome this, we had to create a set of Rector rules that will copy-paste the Doctrine parser class code from  `/vendor`, replace the `constant()` lines with preserving [identifier + value collector](https://github.com/rectorphp/rector/blob/0.10.3/packages/DoctrineAnnotationGenerated/ConstantPreservingDocParser.php#L796-L798) and few more ugly hacks.

This solution was terrible, but it did the job.

## Broken Spaces on Reprint

Last but not least, spaces were completely removed on re-print:

```diff
-* @ORM\Table(name = "my_entity", indexes = {@ORM\Index(
-*     name = "my_entity_xxx_idx", columns = {
-*         "xxx"
-*     }
-* )})
+* @ORM\Table(name="my_entity", indexes={@ORM\Index(name="my_entity_xxx_idx", columns={"xxx"})})
```

We tried to compensate for this with regular expressions, but it was a very crappy solution.

<br>

## Why we Used `doctrine/annotations`?

You may be wondering why we even used doctrine/annotations if it causes so many troubles?

<blockquote class="blockquote text-center mt-5 mb-5">
    "There are no solutions,<br>
    only trade-offs"
</blockquote>


The next other solution was using [phpdoc-parser](https://github.com/phpstan/phpdoc-parser) from PHPStan. The most advanced docblock parser we now have in PHP. The first downside is that it parses Doctrine annotations as `GenericTagValueNode` with all values connected to a long string:

<img src="https://user-images.githubusercontent.com/924196/113859916-b6f77200-97a5-11eb-8818-e6aa2626b719.png" class="img-thumbnail">

Do you need to change "my_entity" to "our_entity"? Use regular expression and good luck.

<br>

## 1. Nodes with Attributes

To make it work, we had to do **2 things**: add attributes to the PhpDoc nodes, the same way nikic/php-parser does:

```php
$phpDocNode->setAttibute('key', 'value');
$phpDocNode->getAttibute('key'); // "value"
```

That would enable format-preserving and nested values juggling, which Doctrine Annotations are known.

We [proposed the attributes in phpdoc-parser 2 years ago](https://github.com/phpstan/phpdoc-parser/issues/11), but it didn't get any traction as phpdoc-parser was also a read-only tool like Doctrine Annotations.

Luckily, it got **revived and [we contributed attributes on each node](https://github.com/phpstan/phpdoc-parser/pull/65) a month ago** and was released under phpdoc-parser 0.5!


## 2. Rewrite Doctrine/Annotation in phpdoc-parser

We also needed values of annotation values using a custom lexer based on phpdoc-parser. This parser should:

- keep constants
- cover nested values, like annotation in an annotation
- cover nested spaces, quotes, `:` or `=`
- keep the original format

<br>

To make it happen, **we had to rewrite [DocParser](https://github.com/doctrine/annotations/blob/1.13.x/lib/Doctrine/Common/Annotations/DocParser.php) from Doctrine to phpdoc-parser syntax**. That included parsing values, arrays, curly arrays with keys, constants, brackets, quotes, and newlines between them.

<br>

11 days later, the final result is here:

<img src="https://user-images.githubusercontent.com/924196/113863434-f3c56800-97a9-11eb-8ca1-70302396cc87.png" class="img-thumbnail">

<br>

Now every Doctrine-like annotation has:

- **single object** to work with
- annotation class with fully qualified class name
- way to modify its values, quoted and silent
- way to modify nested annotations
- automated reprint on a modified node, e.g., if we change `string` to `int`

👍


## How does it help the Rector Community?

With static reflection in annotations, now you can **refactor old projects that use Doctrine Annotations without loading them**.

Refactoring php doc tag nodes is now super easy. E.g., if we wanted to modify `@Method` from Sensio before this refactoring, we had to create a node class, a factory class, register it, autoload the doctrine annotation in a stub and prepare custom methods for custom properties of that specific class.

**And now?**

```php
use Rector\BetterPhpDocParser\PhpDoc\DoctrineAnnotationTagValueNode;

$methodTagValueNode = $phpDocInfo->getByAnnotationClass(
    'Sensio\Bundle\FrameworkExtraBundle\Configuration\Method'
);

if ($methodTagValueNode instanceof DoctrineAnnotationTagValueNode) {
    $values = $methodTagValueNode->getValues();
    // ...
    $methodTagValueNode->changeValue('key', ['value']);
}
```

<br>

Happy coding!
