Sometimes, we want to apply just one rule at a time. But modifying `rector.php` config over and over again is tedious and can lead to mistakes.

To run single rule, we can use `--only` CLI option. Just add quoted class name:

```bash
vendor/bin/rector --only="Rector\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodRector"
```

Mind the quotes, as both Win and *nix system require them to pick up the full class name string (both single and double are supported).

## Rule must be registered in `rector.php`

We can run only the rules explicitly registered in `rector.php` or loaded sets. This way configured rules load explicit configuration the same way we would run the rule by calling `vendor/bin/rector`.

<br>

In case Rector is not able to pick up your class name, see [feature pull-request](https://github.com/rectorphp/rector-src/pull/6441#issuecomment-2497474323) that describes all supported formats.
