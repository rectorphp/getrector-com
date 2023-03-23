---
id: 48
title: "Faster Rector on 0.15.22"
perex: |
    Correctness has more priority than speed. Since version 0.14.x, Rector has better scope refresh handling for multiple rules and handle more crash that happen on 0.13.x. On 0.15.x, Rector give optimization a chance to raise.

since_rector: 0.15.22
---


This performance optimization is contributed by [keulinho](https://github.com/keulinho), which with his knowledge on usage of `blackfire`, provided various PRs on performance optimizations:

- [https://github.com/rectorphp/rector-src/pull/3485](https://github.com/rectorphp/rector-src/pull/3485)
- [https://github.com/rectorphp/rector-src/pull/3495](https://github.com/rectorphp/rector-src/pull/3495)
- [https://github.com/rectorphp/rector-src/pull/3501](https://github.com/rectorphp/rector-src/pull/3501)
- [https://github.com/rectorphp/rector-src/pull/3502](https://github.com/rectorphp/rector-src/pull/3502)
- [https://github.com/rectorphp/rector-symfony/pull/381](https://github.com/rectorphp/rector-src/pull/381)
- [https://github.com/rectorphp/rector-symfony/pull/382](https://github.com/rectorphp/rector-src/pull/382)

## What happened?

1) Memoization resolved data

On various use cases, data can be hit in multiple rules, again and again, that's worth cached, for example:

```php
     /**
     * @var array<string, bool>
     */
    private array $skippedFiles = [];

    public function shouldSkip(string | object $element, string $filePath): bool
    {
        if (isset($this->skippedFiles[$filePath])) {
            return $this->skippedFiles[$filePath];
        }

        $skippedPaths = $this->skippedPathsResolver->resolve();
        return $this->skippedFiles[$filePath] = $this->fileInfoMatcher->doesFileInfoMatchPatterns($filePath, $skippedPaths);
    }
```

Above save data with index `$filePath` to a property, which the service is shared so it won't hit again.

2) Avoid object creation when not needed, for example:

**Before**

```php
if (! $this->isObjectType($methodCall->var, new ObjectType('ReflectionFunctionAbstract'))) {
    return false;
}

return $this->isName($methodCall->name, 'getReturnType');
```

**After**

```php
if (! $this->isName($methodCall->name, 'getReturnType')) {
    return false;
}

return $this->isObjectType($methodCall->var, new ObjectType('ReflectionFunctionAbstract'));
```

Above, no need to create `new ObjectType()` when the name is not `getReturnType`, which faster.


In the `CodeIgniter 4` project, it already show twice faster:

**Before**

<img src="https://user-images.githubusercontent.com/459648/227140283-e93901a5-0975-4eff-97a0-07e8279d0bc8.jpeg" class="img-thumbnail">

**After**

<img src="https://user-images.githubusercontent.com/459648/227140431-14f989e4-d67a-46e2-949c-e392fdd6dc29.jpeg" class="img-thumbnail">

## Another future improvements effort

1) Moving away from `parent` lookup after node found by `NodeFinder` to `SimpleCallableNodeTraverser`, like this this PR:

- [https://github.com/rectorphp/rector-src/pull/3504](https://github.com/rectorphp/rector-src/pull/3504)

Above:

✔️ We know that we search specific `Node`, which is `Assign` node
✔️ No need to traverse deep when we found anonymous class ( `new class` ) and inner function inside `ClassMethod`

2) Replacing lookup all nodes to only found first node, like this PR:

- [https://github.com/rectorphp/rector-src/pull/3505](https://github.com/rectorphp/rector-src/pull/3505)

Above:

✔️ Instead of get all nodes by instance, and search name later, we find first found instance and directly verify the name.

<br>
<br>



Start feel the speed. Run composer update!

<br>

Happy coding!
