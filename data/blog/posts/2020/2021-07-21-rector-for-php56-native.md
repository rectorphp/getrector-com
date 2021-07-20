---
id: 24
title: "Rector for PHP 5.6 Native"
perex: |
    Currently, you can install Rector on PHP 7.1-8.0 with native support. There is no need for Docker, syntax hacking, or running Rector from a different PHP version than your project. Rector is now downgraded from PHP 8.0 to PHP 7.1.
    <br><br>
    To get there, we had to write over 40 downgrade rules. From the first downgrade rule in October 2020 to [release in March 2021](/blog/2021/03/22/rector-010-released-with-php71-support) **it took us 6 months of extensive work**.
---

## Projects Stuck on PHP 5.6

We want to take it a step further and make Rector accessible to those who need it most. Upgrade from PHP 7.1 to 8.0 is a piece of cake compared to 5.6 to 7.0. **Projects stuck on 5.6 have huge troubles to upgrade** because there are no native automated supports. We want to change it to this:

```bash
php --version
# PHP 5.6

composer require rector/rector --dev

vendor/bin/rector process src
# just works... How cool is that?
```

<br>

We'll be honest with you. We **need your help to get there**!

<br>

## Sponsorware

We want to release this package as a [sponsorware](https://github.com/sponsorware/docs).

> Sponsorware is a release strategy for open-source software that enables developers to be compensated for their open-source work with fewer downsides than traditional open-source funding models.

**Why sponsorware?**

* Downgrading is not a simple process. To make it work correctly, we have to explore our code for features that we have to downgrade. That is the easy part. The hard part is every single vendor dependency and also their dependencies.

* Valid downgrade also includes PHP-internal constants, the fallback of newer functions, and syntax order changes. To make this right and provide a Rector that gets you out of PHP 5.6 beyond, we need your help.

* We also want to make sure there is enough interest in the PHP community worth the effort to introduce this version of Rector.

### 1. Early-Access Threshold

At the time we publish this, [we have 22 sponsors](https://github.com/sponsors/TomasVotruba/).

When we **reach 45 sponsors**, we'll release the [rector-php56](https://github.com/rectorphp/rector-php56) version in a matter of 4 weeks for testing and give exclusive access to all sponsors at the time of release and onward. Testing and bug fixes included.

### 2. Open-Source Threshold

When we **reach 70 sponsors**, we'll release [rector-php56](https://github.com/rectorphp/rector-php56) as an open-source package under MIT license.

The repository for release is here: [rectorphp/rector-php5](https://github.com/rectorphp/rector-php5).

<br>

You can [sponsor us here](https://github.com/sponsors/tomasvotruba/).

<br>

Thank you!
