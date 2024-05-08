---
id: 66
title: "5 Tricks to Write Custom Rules"
perex: |
    Rector and its [extensions](https://github.com/rectorphp/rector/?tab=readme-ov-file#empowered-by-community-heart) already consist of many rules for PHP upgrades, Framework upgrades, improve code quality and type coverage, however, you may need your own needs, that's the time you need to write your own custom rules.

    Yes, there is documentation for how to write custom rules [https://getrector.com/documentation/custom-rule](https://getrector.com/documentation/custom-rule), but the following tricks can help you more.
---

## 1. Know what `Node` to be changed **before** vs **after** that is Needed

There are usually 2 kinds of `Node` instance that you can use:

- `PhpParser\Node\Expr`
- `PhpParser\Node\Stmt`

That will ensure the node and it structure always correctly refreshed after refactored, to avoid error:

```bash
Complete parent node of "PhpParser\Node\Attribute" be a stmt.
```

Except you have a very specific use case that it may be ok to not refresh it, eg:

- `PhpParser\Node\Name`
- `PhpParser\Node\Identifier`
- `PhpParser\Node\Param`
- `PhpParser\Node\Arg`
- `PhpParser\Node\Expr\Variable`

The list are in [`ScopeAnalyzer::NON_REFRESHABLE_NODES` constant](https://github.com/rectorphp/rector-src/blob/650dcc6394c6df206772350e525311f8080e5077/src/NodeAnalyzer/ScopeAnalyzer.php#L19).

To know what `Node` is needed to be changed, you can see visual documentation of PHP Parser nodes [https://github.com/rectorphp/php-parser-nodes-docs](https://github.com/rectorphp/php-parser-nodes-docs), that you can see online at Play with AST Page [https://getrector.com/ast](https://getrector.com/ast) to see what target node to be used. We have a blog post for that at [https://getrector.com/blog/introducing-play-with-ast-page](https://getrector.com/blog/introducing-play-with-ast-page).

## 2. Set Return `null` on no change, return the `Node` or array of `Nodes` on changed

For example:

```php
public function refactor(Node $node): ?Node
{
    if ( /* some condition */ ) {
        return null;
    }

    if (/* some condition */) {
        // make a change to Node
        return $node;
    }

    // return array of nodes, usually array of Stmt[],
    // eg: insert new line before existing stmt
    return [
        new \PhpParser\Node\Stmt\Nop(),
        $node,
    ];
}
```

## 3. XXX

xxx

<br>

Happy coding!
