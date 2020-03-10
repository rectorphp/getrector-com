---
id: 3
title: "Upgrading Glami to PSR-4 part 2: How?"
perex: |
    In june 2019 we upgraded [Glami](https://glami.cz)'s big codebase to be PSR-4 compatible.
    <br>
    <strong>It was a great success! In this part we will go through the migration process.</strong>
tweet: "New post on #rectorphp blog: Upgraded Glami to PSR-4, part 2: How?"
---

## What were we migrating?

LOC table

## How we achieved it

First of all, we had to plan everything properly.

In Glami team, we set up this expectations:
- Must NOT slow application for more than 5 %
- Must NOT block other developers from delivering features
- Must be repeatable (there was high chance that during merging new commits with new classes will appear)
- Bug-free - Glami **MUST NOT GO DOWN**! (not even 1s downtime is allowed, Glami has processes for it!)

## Edge cases consumed 90% of the time

Not everything went as smooth as we planned :-). There were some edge cases, that were not really easy to discover and caused total system failure - had to be solved quickly to continue in the migration process.

- Class names as strings
```diff
- $this->createMockBuilder('MyClass');
+ $this->createMockBuilder(MyClass::class);
```
- PHP-Parser could not parse empty annotation with extra spaces (it was very hard discoverable bug)
```diff
- /** @var  */
+ /** @var */
private $property;
```
- Using class access in templates
- There was a bug in Rector itself with importing classes from same namespace, so we decided to work with FQCN only
```diff
namespace Namespace;

- class MyClass extends MyOtherClass
+ class MyClass extends \Namespace\MyOtherClass
```

## Getting changes live

Code review, too many changed files etc.

---

Some of the source code used to migrate is shared on [Glami's github](https://github.com/glami/psr-4-migration).
