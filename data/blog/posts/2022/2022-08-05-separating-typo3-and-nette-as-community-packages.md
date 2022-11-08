---
id: 38
title: "Separating Typo3 and Nette as Community Packages"
perex: |
    When Rector started, it was a small project that handled upgrading a vast amount of PHP packages.
    <br><br>
    As the project grew and expanded, more local PHP communities joined with community packages that build custom rules on top Rector core.
    <br><br>
    It makes sense to separate these projects from the core and let the community handle them. Who does a better job at growing the vegetable than farmers themselves, right?

since_rector: 0.14
---

To this day, there are around 10 known community packages that are [maintained by the community](https://github.com/rectorphp/rector#empowered-by-rector-community-heart). These include [CraftCMS](https://github.com/craftcms/rector), [Shopware](https://github.com/FriendsOfShopware/shopware-rector) or famous [Drupal Rector bot](https://www.drupal.org/blog/accelerating-drupal-9-module-and-theme-readiness-with-automated-patches) that automates the upgrades for Drupal 9.

## Communities in Control of Their Standards

The significant advantage of community-maintained packages over core ones is that every community has its standards. Those standards are specific to the particular community but not useful for general Rector users. E.g., Typo3 uses XML files that can be automated, too, and Nette uses NEON files for service registrations, etc.

We want to give these communities the freedom to implement any feature their framework needs. When Typo3 and Nette packages were part of the core, these features were often evaluated with strict questions "how does the Rector community benefit from it"? This approach leads to a collision between two unrelated worlds.

Why have frictions when these 2 projects can cooperate and work better apart?

Also, Rector users who do not use these community packages, can benefits from this change. Their Rector install load is now smaller and pulls less dependencies.

## Community Leaders with Strong Vision

Second, the framework communities are driven by their passionate leaders. There is no Symfony without Fabien, no Laravel without Taylor. Leaders need freedom, responsibility, and power to decide where the project should go. Of course, they discuss their opinions with others before making a move, but in the end, it is their decision to move in this or that direction.

That's why we believe the community package should be in the hands of people who use the framework daily. A new framework version brings new features every year, and the person to convert them to Rector rules is a passionate developer with a taste for innovation and bleeding edge.

<br>

That's why we decided to separate typo3 and Nette from the core and let their active communities take over. They're not part of `rector/rector` anymore, but they're Rector extensions that you can install yourself if you need those:

<br>

### How to upgrade to Typo3 community Rector package?

Add [new package](https://github.com/sabbelasichon/typo3-rector) via composer:

```bash
composer require sabbelasichon/typo3-rector --dev
```

<br>

### How to upgrade to Nette community Rector package?

Add [new package](https://github.com/efabrica-team/rector-nette) via composer:

```bash
composer require efabrica/rector-nette --dev
```

<br>

And replace namespace:

```diff
-Rector\\Nette\\
+RectorNette\\
```

That's it!

<br>

We believe this brings faster iterations of the packages and focuses on a stronger Rector core to support their growth.
