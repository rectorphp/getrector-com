---
id: 36
title: "New in Rector 0.13 - Refresh Scope for Changed Nodes"
perex: |
    Rector is using PHPStan to detect types of various expressions. That means every node has access to [PHPStan `Scope`](https://phpstan.org/developing-extensions/scope), e.g., with types or class reflection. From code `$value = 1;` we know, that `$value` is type of int. But what if we change the node?

contributor: TomasVotruba
pull_request_id: 2292

since_rector: 0.13
---

Let's say the Rector changes the code the following way:

```diff
-$value = 1;
+$value = 'yes';
```

The `$value` was an `int` type. But after the change, the type is gone. We have an old `Scope` object where `$value` is still `int` or even worse. If we create a new `Assign` node, no `Scope` is available anymore.

## Changed Nodes with Lost Scope

This becomes problematic when we rename the variable. Suddenly PHPStan has no idea about the new variable type, and everything is `mixed` for it. When we use `CountOnNullRector` that prevents `count()` on `null` fatal error, we can see Rector changed like these:

```diff
-$items = [];
+$posts = [];
-echo count($items);
+echo is_array($posts) ? count($posts) : 0;
```

Which is obviously wrong.

## Scope Refresh to the Rescue

To solve this problem, we implemented a new feature called "scope refresh" that rebuilds the scope based on the changed node. When a variable name changes, the scope will know about it and keep its type. It's a challenge as PHPStan `Scope` object has few design limitations. It's an immutable object - once you enter a class, you cannot enter it again.

In the end, we made it work so that every new node will be traversed again with a new `Scope` object.

That will allows Rector to be aware of the node type, even if nodes change:

```diff
-$items = [];
+$posts = [];
-echo count($items);
+echo count($posts);
```

This is feature coming in Rector 0.13. In the meantime, don't forget to [upgrade to `RectorConfig`](/blog/new-in-rector-012-introducing-rector-config-with-autocomplete).

<br>

Happy coding!
