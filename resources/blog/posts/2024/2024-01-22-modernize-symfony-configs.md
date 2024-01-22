---
id: 56
title: "Modernize Symfony Configs"
perex: |
    Symfony configuration is one of changes that are quite difficult to spot, until they're removed in next major version. Then you have to Google the "invalid option id error message" and hope for a solution. That's doesn't sound like a good way to spend your weekend, does it?

    Symfony actually adds deprecation message to those options, but they're not easy to spot.

    Today, we'll show you how spot them with help of Rector, PHPStan and one other cool tool.
---

Symfony security is known for major changes in almost every Symfony version. What worked in the past...

```yaml
# config/security.yml
security:
    enable_authenticator_manager: true
```

...can be changed or removed. This actually does not work in Symfony 7. We want to be warned, early, as soon as the change happens and without running our code.
Other PHP projects use `@deprecation` annotations, that highlight deprecated methods, properties, constant and classes right in code in your favorite IDE.

How about YAML? It does not, that's why we first have to migrate to PHP.

## 1. Move from YAML to PHP

Not necessary to bother with manual flip. Use [symplify/config-transformer](https://github.com/symplify/config-transformer) to automate the process instead:

```bash
composer require sympify/config-transformer --dev
vendor/bin/config-transformer switch-format config/security.yml
```

It parses YAML, maps it into [fluent PHP format](https://symfony.com/blog/new-in-symfony-3-4-php-based-configuration-for-services-and-routes) and prints it out:

```php
# config/security.php
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('security', [
        'enable_authenticator_manager' => true,
    ]);
};
```

Job done!

If it's your first PHP fluent config, don't forget to [update Kernel to load PHP files too](https://tomasvotruba.com/blog/2020/07/27/how-to-switch-from-yaml-xml-configs-to-php-today-with-migrify/).

<br>

Now we have PHP configs, but in reality - it's just another form of array full of strings. Symfony can do better, at least since Symfony 5.3.

<br>

## 2. Move from array of strings, to typed PHP objects

Instead of arrays, we want something that we can use in our IDE - fully typed objects with method autocomplete.

Are on Symfony 5.3+? If not, upgrade with Rector and then use [Config Builder Classes](https://symfony.com/blog/new-in-symfony-5-3-config-builder-classes). It's a bit tricky to tell your IDE and PHPStan about them, because they're not part of the code. They're generated on the fly based on your current Symfony version.

To make them work with IDE and PHPStan out of the box, we have to generate them first. To streamline the process, use simple `tomasvotruba/symfony-config-generator` tool, that detects available extension classes and generates config builder classes for us:

```bash
composer require tomasvotruba/symfony-config-generator --dev
vendor/bin/symfony-config-generator
```

You can generated classes in your Symfony cache directory:

```bash
/var/cache/Symfony
```

<br>

We have config builder classes available, and PHP with arrays of string. **How can we flip those arrays to config builder classes?**

Rector to the rescue! Symfony rules for Rector contain one little gem that helps us automate 99 % of work - `StringExtensionToConfigBuilderRector` rule. Add it to your `rector.php` config:

```php
# rector.php
use Rector\Config\RectorConfig;
use Rector\Symfony\CodeQuality\Rector\Closure\StringExtensionToConfigBuilderRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rules([
        StringExtensionToConfigBuilderRector::class,
    ]);
};
```

Then run Rector on config file:

```bash
vendor/bin/rector p config/security.php
```

And voilá - here is your state of art Symfony security config:

```php
# config/security.php
use Symfony\Config\SecurityConfig;

return static function (SecurityConfig $securityConfig): void {
    $securityConfig->enableAuthenticatorManager(true);
```

You have just increased value of your configs by order of magnitude:

* full IDE autocomplete of any security configuration
* no more copy-pasting `"firewall"` string and reading Symfony docs options
* you get warnings by PHPStan, if we ever make a typo

<br>

To make PHPStan work and not to complain about missing class located in `/var/cache`, we have to load classes in PHPStan config:

```neon
# phpstan.neon
parameters:
    bootstrapFiles:
        - var/cache/Symfony/Config/SecurityConfig.php
        - var/cache/Symfony/Config/Security/ProviderConfig.php
        - var/cache/Symfony/Config/Security/AccessControlConfig.php
        - var/cache/Symfony/Config/Security/AccessDecisionManagerConfig.php
        - var/cache/Symfony/Config/Security/FirewallConfig.php
        - var/cache/Symfony/Config/Security/PasswordHasherConfig.php
        - var/cache/Symfony/Config/Security/ProviderConfig.php
```

<br>

## 3. Harvest full power of Static Analysis

We'd love to work with Symfony project in such a shape, but wait - there is more.

What we really want is to **get early warnings about deprecations right in our CI** before we decide to upgrade. The configs are not available in the Symfony Github repository, as they're generated on the fly.

But when we look at the file in our project:

```bash
var/cache/Symfony/Config/SecurityConfig.php
```

We'll see the `enableAuthenticatorManager()` method is actually deprecated in Symfony 6.4:

```php
    /**
     * @default true
     * @param ParamConfigurator|bool $value
     * @deprecated The "enable_authenticator_manager" option at "security" is deprecated.
     * @return $this
     */
    public function enableAuthenticatorManager($value): static
    {
        $this->_usedProperties['enableAuthenticatorManager'] = true;
        $this->enableAuthenticatorManager = $value;

        return $this;
    }
```

<br>

Now we have:

* configs in PHP methods
* PHPStan available
* we use method with `@deprecated` marker

But that's not enough to let CI help us. One piece is missing... care to guess?

<br>

PHPStan provides an extension that reports `@deprecated` elements right in the CI:

```bash
composer require phpstan/phpstan-deprecation-rules --dev

# include phpstan extension installer, if you don't have it yet
composer require phpstan/extension-installer --dev
```

<br>

Let's run PHPStan on our config to see result:

```bash
vendor/bin/phpstan a config/security.php
```

The PHPStan fails and let us know:

```bash
Note: Using configuration file phpstan.neon.

1/1 [▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓] 100%

------ ------------------------------------------------------------------------------------------------
 Line   config.php
------ ------------------------------------------------------------------------------------------------
 8      Call to deprecated method enableAuthenticatorManager() of class Symfony\Config\SecurityConfig:
        The "enable_authenticator_manager" option at "security" is deprecated.
------ ------------------------------------------------------------------------------------------------
```

Now you're covered and can start fixing deprecations before they're gone comletelly.

<br>

This takes a while to setup for first timers, but you'll get quickly into it.
The value returns thousand folds in long term as you'll get the best Symfony configuration there is.

<br>

Happy coding!
