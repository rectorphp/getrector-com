---
id: 10
title: "4 Configurable PHPStan rules that Help Rector Merge 188 pull-requests a Month"
perex: |
    Last month, we merged a total [188 pull-requests](https://github.com/rectorphp/rector/pulse/monthly) to Rector code. We could not afford such a high rate without having a robust CI setup we trust. Dozens of custom PHPStan rules help us on every commit.

    Today we'll share with you 4 of them. You can use them in your code to **save time** and let robots work for you.
---

Following PHPStan rules saves us from pushing bugs to GitHub. They also save us precious time in code-reviews commenting obvious errors - that are easy to miss in huge pull-requests. They allow us not to think about code details but about business logic or architecture design.

## 1. When PHPStorm Imports the Wrong Short Class

Rector is using php-parser and it's nodes, e.g. `PhpParser\Node\Expr\Variable`.

A Rector rule that should change `$something` would look like this:

```php
use Symfony\Component\DependencyInjection\Variable;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use PhpParser\Node;
use Rector\Rector\AbstractRector;

final class ChangeSomethignRector extends AbstractRector
{
    public function getNodeTypes() : array
    {
        return [Variable::class];
    }

    /**
     * @param Variable $node
     */
    public function refactor(Node $node) : ?Node
    {
        // ...
    }

    // ...
}
```

Now look at the code again, and try to find the bug.

Found it!

```diff
-use Symfony\Component\DependencyInjection\Variable;
+use PhpParser\Node\Expr\Variable;
```

There is more than 2 option actually:

<img src="/assets/images/blog/2020/variable-type-sometimes.png" class="img-thumbnail">

PHPStorm has no idea which class you want to use. When we code, we don't have time or thought about which imports are right. 99 % it's the first one. Of course, PHPStorm can learn from our manual choices and prefer the most selected one. But that means every contributor has to teach their PHPStorm, to avoid these bugs. That's non-sense.

Instead, we added known miss-types that appeared in pull-requests to PHPStan checks. Not only the wrong type but **neatly with a suggestion for the right node class**.

```yaml
# phpstan.neon
services:
    -
        class: Symplify\PHPStanRules\Rules\PreferredClassRule
        tags: [phpstan.rules.rule]
        arguments:
            oldToPreferredClasses:
                'PHPUnit\TextUI\Configuration\Variable': 'PhpParser\Node\Expr\Variable'
                'Symfony\Component\DependencyInjection\Variable': 'PhpParser\Node\Expr\Variable'
                'phpDocumentor\Reflection\Types\Expression': 'PhpParser\Node\Stmt\Expression'
                'phpDocumentor\Reflection\DocBlock\Tags\Param': 'PhpParser\Node\Param'
                'phpDocumentor\Reflection\DocBlock\Tags\Return_': 'PhpParser\Node\Stmt\Return_'
                'SebastianBergmann\Type\MixedType': 'PHPStan\Type\MixedType'
                'Hoa\Protocol\Node\Node': 'PhpParser\Node'
```

The [PreferredClassRule](https://github.com/symplify/symplify/blob/master/packages/phpstan-rules/src/Rules/PreferredClassRule.php) works in 100 % of our cases. We never had to use the left type in our code.

## 2. From Class to its Test and Back Again

When we work with Rector, we either create new rules or fix bug in existing rule. To fix a bug, we [add failing test fixture](https://github.com/rectorphp/rector/blob/master/docs/how_to_add_test_for_rector_rule.md), then we switch to the rule class and fixed the bug.
Add a test fixture, fix it... Add a test fixture, fix it...

**That means constant jumping between rule and its tests**. To save cognitive overload, we add a `@see` annotation above every rule class:

<img src="/assets/images/blog/2020/see-test-jumping.gif" class="img-thumbnail">

This way **we avoid a long search for the correct class** and just jump right to it with one click.

Does every new rule has this annotation? **No need to bother each other during code-reviews**, because PHPStan has our back:

```yaml
# phpstan.neon
services:
    -
        class: Symplify\PHPStanRules\Rules\SeeAnnotationToTestRule
        tags: [phpstan.rules.rule]
        arguments:
            requiredSeeTypes:
                - Rector\Rector\AbstractRector
```

<br>

An unintended side effect of the [SeeAnnotationToTestRule](https://github.com/symplify/symplify/blob/master/packages/phpstan-rules/src/Rules/SeeAnnotationToTestRule.php) is that **every Rector rule is tested**.

Do you want to add this PHPStan rule yourself but don't like hundreds of PHPStan errors in your CI?
Use [this Rector rule to complete `@see` annotations](https://github.com/rectorphp/rector/blob/master/docs/rector_rules_overview.md#addseetestannotationrector) for you.

## 3. Only one Optional Method

Rector test cases can provide configuration in 3 different methods:

- only Rector class in `getRectorClass()` method
- Rector class with configuration in `getRectorsWithConfiguration()` method
- full path to `config.php` with services in `provideConfigFileInfo()` method

At least that was the idea. In reality, it was possible to mix these methods:

```php
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class SomeRectorTest extends AbstractRectorTestCase
{
    public function getRectorClass()
    {
        return SomeRector::class;
    }

    // WTF? why is this here?
    // it only duplicates the previous method
    public function getRectorsWithConfiguration(): array
    {
        return [
            SomeRector::class => [],
        ];
    }
}
```

It didn't cause any runtime troubles, but it was a code smell. To avoid [more broken windows](https://blog.codinghorror.com/the-broken-window-theory/), we've added a custom PHPStan rule.

This [OnlyOneClassMethodRule](https://github.com/symplify/symplify/blob/master/packages/phpstan-rules/src/Rules/OnlyOneClassMethodRule.php) checks classes of specific type(s), and makes sure there **is exactly 1 method** used from defined list:

```yaml
# phpstan.neon
services:
    -
        class: Symplify\PHPStanRules\Rules\OnlyOneClassMethodRule
        tags: [phpstan.rules.rule]
        arguments:
            onlyOneMethodsByType:
                Rector\Testing\PHPUnit\AbstractRectorTestCase:
                    - 'getRectorClass'
                    - 'getRectorsWithConfiguration'
                    - 'provideConfigFileInfo'
```

## 4. Avoid Known Types Re-Checks

This rule is not configurable, but it saved **us so much duplicated work we have to mention it**.

Look at the following code. What would you improve type-wise?

```php
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;

class SomeClass
{
    public function run(Node $node)
    {
        if ($node instanceof MethodCall) {
            $this->isCheck($node);
        }
    }

    private function isCheck(Node $node)
    {
        if (! $node instanceof MethodCall) {
            return;
        }

        // ...
    }
}
```

<br>

What about this?

```diff
-   private function isCheck(Node $node)
+   private function isCheck(MethodCall $methodCall)
    {
-       if (! $node instanceof MethodCall) {
-           return;
-       }

        // ...
    }
```

Yes, why would you check `MethodCall` that it's `MethodCall` again? It might be evident in the post example with ten lines and nothing else. But in real life, we can easily miss it in any pull-request of 30+ lines.
Now PHPStan has again our back with the [CheckTypehintCallerTypeRule](https://github.com/symplify/symplify/blob/master/packages/phpstan-rules/src/Rules/CheckTypehintCallerTypeRule.php):

```yaml
# phpstan.neon
services:
    -
        class: Symplify\PHPStanRules\Rules\CheckTypehintCallerTypeRule
        tags: [phpstan.rules.rule]
```

Thanks to [`@samsonasik`](https://github.com/samsonasik) for contributing and further improving this last rule. It's a real time-saver.

<br>

You can find all mentioned rules in [symplify/phpstan-rules](https://github.com/symplify/phpstan-rules). Get them, use them. Your code will thank you.

That's all for today.

<br>

Happy coding!
