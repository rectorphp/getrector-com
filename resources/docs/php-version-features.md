Rector is working with PHP features of your project and uses only features, compatible with your code. That means it will not add attributes, unless you're at least on PHP 8.

The best practise is to let pickup the PHP version from `composer.json`:

```json
{
    "require": {
        "php": "^7.4"
    }
}
```

<br>

If it's not there, Rector look into other places:

* PHP version defined in `rector.php`
* `composer.json` require of PHP
* `composer.json` config platform of PHP
* the current PHP version runtime

<br>

If you want to force different PHP version than your codebase, you can do it at your own risk in `rector.php`:

```php
<?php

use Rector\Config\RectorConfig;
use Rector\ValueObject\PhpVersion;

return RectorConfig::configure()
    ->withPhpVersion(PhpVersion::PHP_80);
```

<br>

## Supporting Version Ranges

If your project supports multiple PHP versions at once, you can configure Rector for a version **range** instead of a single target.

<br>

### Downgraded Releases

Using [downgrade rules](https://github.com/rectorphp/rector-downgrade-php), you can write and commit code in your highest supported PHP version, using Rector to keep up-to-date in `rector.php`.

```php
<?php

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPhpSets();
```

Then, in a separate `rector-downgrade.php`, we can specify our minimum version:

```php
<?php

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withDowngradeSets(php82: true);
```

Finally, update your CI to:

1. Run Rector with `rector-downgrade.php`.
2. Update the `php` version constraint in `composer.json` to the downgraded target version.
3. Create a detached commit and tag it with your release version.
4. Push the new release tag to your repository.

You can see an example of this workflow in the [Easy Coding Standard CI](https://github.com/easy-coding-standard/easy-coding-standard/blob/main/.github/workflows/downgraded_release.yaml).

<br>

You may also publish multiple separate releases with different version support, such as releasing version `6.0.0` with two separate point releases: `6.0.0.85` and `6.0.0.84`. You can read more about that workflow in:

- [Rector Rules for PHP Downgrade](https://github.com/rectorphp/rector-downgrade-php)
- ["How to bump Minimal PHP Version without Leaving Anyone Behind?"](https://getrector.com/blog/how-to-bump-minimal-version-without-leaving-anyone-behind)
- ["How all Frameworks can Bump to PHP 8.1 and You can Keep Using Older PHP"](https://getrector.com/blog/how-all-frameworks-can-bump-to-php-81-and-you-can-use-older-php)

<br>

The downsides with this approach are two-fold:

1. It requires a more complex release workflow and CI configuration.
2. You must be responsible for making sure your project never specifies dependencies that are not compatible with your minimum supported version.

<br>

### Forward-Compatible Sets

Alternatively, you can override the target version of PHP for Rector to your minimum supported version:

```php
<?php

use Rector\Config\RectorConfig;
use Rector\ValueObject\PhpVersion;

return RectorConfig::configure()
    ->withPhpSets(php85: true)
    ->withPhpVersion(PhpVersion::PHP_82);
```

*Please note that `withPhpVersion(...)` **must** come after `withPhpSets(...)`.*

Rector will attempt to upgrade your project to the PHP set you provide, but avoid applying any rules not compatible with your minimum-supported version.

<br>

By default, deprecation-related rules only run if your minimum supported version is at or above the version the deprecation was introduced - even if compatible.

If you want Rector to apply deprecation fixes earlier, when the replacement is still compatible with your minimum version, enable eager deprecation resolution:

```php
<?php

use Rector\Config\RectorConfig;
use Rector\ValueObject\PhpVersion;

return RectorConfig::configure()
    ->withPhpSets(php85: true)
    ->withPhpVersion(PhpVersion::PHP_82)
    ->withEagerlyResolvedDeprecations();
```

<br>

This comes with disadvantages compared to the downgraded releases approach:

- Your project cannot use all the newest features in PHP.
- You must be careful that the `->withPhpVersion(...)` is correct or you risk incompatible changes.
- Because not all versions support all features, downstream projects on the highest supported version may experience unwanted deprecation warnings or even **broken features** if rules for breaking changes could not be applied.
