---
id: 42
title: "Support for Nested Doctrine Annotation to Flat Attributes in Rector 0.14"
perex: |
    We added support for [annotation to attribute upgrade](/blog/how-to-upgrade-annotations-to-attributes) in Rector 0.12. Since then, PHP 8.1 has come with nested attributes. Rector supports these, e.g., for Symfony validator.
    <br><br>
    Yet, Doctrine already took a path of its own and **unwrapped nested annotations to flat attributes** to be exclusively open to PHP 8.0 users.
    Next Rector comes with support for these too.

since_rector: 0.14
---


One of the annotations that got unwrapped is `Doctrine\ORM\Mapping\Table`, where `indexes` and `uniqueConstraints` have their own attributes:

```php
use Doctrine\ORM\Mapping as ORM;

#[ORM\Index(name: 'index_key')]
#[ORM\UniqueConstraint(name: 'unique_key')]
```

Adding support for this attribute is pretty straightforward, as every unique annotation class has its unique attribute class.

<br>

Then Doctrine came with the next-level challenge. Unwrap array of `JoinColumn` annotations, once to `JoinColumn` attribute, and once to [`InverseJoinColumns` attribute](https://www.doctrine-project.org/projects/doctrine-orm/en/2.13/reference/attributes-reference.html#joincolumn-inversejoincolumn). Based on parent key.

```php
    use Doctrine\ORM\Mapping as ORM;

    /**
     * @ORM\JoinTable(name="join_table_name",
     *     joinColumns={
     *          @ORM\JoinColumn(name="target_id"),
     *     },
     *     inverseJoinColumns={
     *          @ORM\JoinColumn(name="another_id")
     *     }
     * )
     */
    private $collection;
```

<br>

To handle this specific situation, we added brand new [`NestedAnnotationToAttributeRector` rule](https://github.com/rectorphp/rector-src/pull/2781) to cover.

The case above would be handled by such configuration:

```php
use Rector\Config\RectorConfig;
use Rector\Php80\Rector\Property\NestedAnnotationToAttributeRector;
use Rector\Php80\ValueObject\NestedAnnotationToAttribute;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(NestedAnnotationToAttributeRector::class, [
        new NestedAnnotationToAttribute('Doctrine\ORM\Mapping\JoinTable', [
            'joinColumns' => 'Doctrine\ORM\Mapping\JoinColumn',
            'inverseJoinColumns' => 'Doctrine\ORM\Mapping\InverseJoinColumn',
        ]),
    ]);
```

<br>

This rule will intelligently split the annotations:

```php
    use Doctrine\ORM\Mapping as ORM;

    #[ORM\JoinTable(name: 'join_table_name')]
    #[ORM\JoinColumn(name: 'target_id')]
    #[ORM\InverseJoinColumn(name: 'another_id')]
    private $collection;
```

<br>

## Doctrine Support OnBoard

Do you use the Doctrine upgrade set?

```php
use Rector\Config\RectorConfig;
use Rector\Doctrine\Set\DoctrineSetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->sets([
        DoctrineSetList::ANNOTATIONS_TO_ATTRIBUTES,
    ]);
};
```

We've got you covered. You don't need to fill the particular annotations because [the set is already extended](https://github.com/rectorphp/rector-doctrine/blob/bdf6e7c07b91df02000fa286e30e74c7fb7e5301/config/sets/doctrine-annotations-to-attributes.php#L12-L28).

Just upgrade to Rector 0.14.1, and you're good to go.

<br>

Happy coding!
