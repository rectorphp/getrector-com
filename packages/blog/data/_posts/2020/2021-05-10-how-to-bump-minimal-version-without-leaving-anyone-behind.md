---
id: 23
title: "How to bump Minimal PHP Version without Leaving Anyone Behind?"
perex: |
    Last week we introduced [Prefixed Rector by Default](/blog/prefixed-rector-by-default). Main advantage of this release is that you have single package to install, with no conflicts and minimal PHP version.
    <br><br>
    Rector package can be used on PHP 7.1+ platforms. Yet, we bumped minimal version to PHP 8. Is that a BC break?
---

Isn't that an irony that tool that focuses on **instant upgrades of legacy codebase wants to require the latest PHP** as minimum in `composer.json`? Aren't developers using PHP 8 the group of people that would never use Rector? Why upgrade to PHP 8, if we already have it?

In last post, we shared a new architecture [of development and distribution repository](/blog/prefixed-rector-by-default). In short, this is current situation:

## 1. Development Repository

This repository where we send pull-request and change the code. We can use development dependencies, do any change in the code and test Rector rules with PHPUnit, Rector, ECS and PHPStan.

Its current `composer.json`:

```json
{
    "name": "rector/rector-src",
    "require": {
        "php": ">=7.3"
    }
}
```

This package cannot be installed through `composer require`, as its not designed for end users. If we would make a cake, it's a <těsto> :)

## 2. Release Repository

This repository is a read only and published from `rector/rector-src`. It's for developer who want to use Rector in their projects like:

```bash
composer require rector/rector --dev
```

How is it different from `rector/rector-src`? It's prefixed and downgraded to PHP 7.1. It include the dependencies from `/vendor`, that are prefixed and downgraded too. Do you know PHAR? This is similar, just unpackad.


It's current `composer.json`:

```json
{
    "name": "rector/rector",
    "require": {
        "php": ">=7.1"
    }
}
```

## Single PHP Version bump Leads to Chain of BC Breaks

Bumping min. version in a package usually mean that every package user has to upgrade their PHP version too to be able to use the new package.

E.g. if/when Symfony 6 will require at least PHP 8 and you will have still PHP 7.4, you need to upgrade your PHP first to PHP 8 and only then you can install Symfony 6. This seems like one step task, but **there are few traps**.

If you upgrade PHP version, all the packages in your vendor have to work on the new version too. New PHP version is often connected to a major package release (e.g. Symfony 5 → 6). And what does major package release mean for changes? There **will be BC breaks**.

**One package changed single PHP version requirement** and soon you have to take a 3 months windows to upgrade your whole project and it's dependencies.

## Require min. PHP 8?

Despite this, we decided to bump min PHP version for Rector code base to PHP 8.

```diff
 {
     "name": "rector/rector-src",
     "require": {
-        "php": ">=7.3"
+        "php": ">=8.0"
     }
 }
```

"What? Are you serious?"

Yes. Now look more carefully on the `composer.json` change. It's not `rector/rector`, but `rector/rector-src`.

"So no changed for us end-users? We will be still able to use Rector on PHP 7.1, even you build it in PHP 8?"

Exactly!

"That's amazing!"

Yes it is.


## How can Symfony 6 require PHP 8 and run PHP 7.1?

How can PHP community benefit from this release model? Let's look at about use case we talked about before.

Symfony 6 will require PHP 8. But some people are stuck on PHP 7.x version because of their project is on single server with 50 other projects (or any other valid reason). Yet the would love to use Symfony 6 feature. Why? One reason for all - every single minor release has few super cool DX improvements.

So how can we do that for both groups?

## Monorepo Combo!

We're lucky here, the Symfony repository is using monorepo architecture. That means all the developments happens in `symfony/symfony` and packages are split into smaller read-only repositories:

- `symfony/console`
- `symfony/event-dispatcher`
- ...

Can you see the pattern Symfony, PHPStan and Rector share?

We can add a "middleware" operation, thanks to the way the split happens on release.
This middleware operation will downgrade code from PHP 8 to PHP 7.1 and the tag will be published with code on PHP 7.1.

## Per-PHP Versions

"So if Symfony 6 is out, there will be only PHP 7.1 version? What if I have 2 projects and I want to make use of PHP 8 native code?"

No worries. Each release should have per PHP version tag.

- Do you want to install Symfony 6 with PHP 8? Not a problem.
- Do you want to install Symfony 6 with PHP 7.1? Yes, we can do that

"How do we have to install package that fits our version? Will there be `symfony/console-php71`?"

That would be a way to go, but it would only bother users with learning new packages names for implementation detail they really don't care about. That's why we merged `rector/rector` and `rector/rector-prefixed`.

## Composer Semver to the Rescue

Instead we can make use of simple tagging, adding last 2 digits to state PHP version.

- `6.0.0` - native release without downgrade - no need to version
- `6.0.0.71` - downgraded release to PHP 7.1

```bash
# on PHP 8
composer require symfony/console:^6.0
...installing symfony/console:6.0.0

# on PHP 7.1
composer require symfony/console:^6.0
...installing symfony/console:6.0.0.71
```

This way if any package will require `symfony/console:^6.0`, they will always match current set of features.

<em class="fas fa-fw fa-check text-success fa-2x"></em>

For now it's just an idea. Yet we already can see working ~~prototype~~ examples in PHP world. PHPStan is using this release approach since (@todo). This week Rector and [ECS has joined](@tiwtterl ink).

<blockquote class="blockquote">
The question is not "how can we do it",<br>
but "who wil be the next"?
</blockquote>

<br>


Happy coding!
