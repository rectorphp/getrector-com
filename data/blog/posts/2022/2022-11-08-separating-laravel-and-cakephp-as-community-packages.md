---
id: 43
title: "Separating Laravel and CakePHP as Community Packages"
perex: |
    Rector is built for and on the whole PHP community right from the start. But there are also somewhat "local" PHP communities around a specific framework. Each framework has specific needs that are best known to the community member.
    <br><br>
    That's [why we entirely moved Typo3 and Nette](/blog/separating-typo3-and-nette-as-community-packages) Rector extensions **to their communities**. They know best how to handle rules for the framework.
    <br><br>
    We want to encourage the community to build their own packages on top of Rector core, so we also decided to move Laravel and CakePHP to the community.

since_rector: "0.14.7"
---

<blockquote class="twitter-tweet"><p lang="en" dir="ltr">Based on a great experience with other frameworks, it&#39;s time to give <a href="https://twitter.com/hashtag/laravel?src=hash&amp;ref_src=twsrc%5Etfw">#laravel</a> <a href="https://twitter.com/rectorphp?ref_src=twsrc%5Etfw">@rectorphp</a> package to the community it belongs to 🙏<br><br>We look for person, who will take over current package <a href="https://t.co/S1rlIiRfZr">https://t.co/S1rlIiRfZr</a> and builds it further into propper &quot;Laractor&quot;😉</p>&mdash; Rector (@rectorphp) <a href="https://twitter.com/rectorphp/status/1585361016262639616?ref_src=twsrc%5Etfw">October 26, 2022</a></blockquote>

<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>

<br><br>

## Communities in Control of Their Standards

The significant advantage of community-maintained packages over core ones is that every community has its standards. Those standards are specific to the particular community but not useful for general Rector users. E.g., Laravel uses Blade, a PHP template syntax that can be automated, too.

We want to give these communities the freedom to implement any feature their framework needs. Having these packages in the core, where there are no Laravel and CakePHP developers, only drags those down.

As a side effect, Rector users who do not use particular community packages benefit from this too. Their Rector install load is now smaller and pulls fewer dependencies.

## Community Leaders with Strong Vision

Second, the framework communities are driven by their passionate leaders. There is no Symfony without Fabien, no Laravel without Taylor. Leaders need freedom, responsibility, and power to decide where the project should go. Of course, they discuss their opinions with others before making a move, but in the end, it is their decision to move in this or that direction.

That's why the community package should be in the hands of people who use the framework daily. A new framework version brings new features every year. The person who converts them to Rector rules is a passionate developer with a taste for innovation and a bleeding edge.

<br>

That's why we decided to separate Laravel and CakePHP from the core and let their active communities take over. They're not part of `rector/rector` anymore, but they're Rector extensions that you can install standalone.

<br>

### How to upgrade to the Laravel community Rector package?

Add [new package](https://github.com/driftingly/rector-laravel) via composer:

```bash
composer require driftingly/rector-laravel --dev
```

<br>

**Big thanks to [Anthony Clark, aka driftingly](https://github.com/driftingly)** from Tighten for making this happen promptly and with a smooth swipe 🙏

<br>

### How to upgrade to the CakePHP community Rector package?

Add [new package](https://github.com/cakephp/upgrade) via composer:

```bash
composer require cakephp/upgrade --dev
```

<br>

That's it!


<br>

We believe this brings faster iterations of the packages and focuses on a stronger Rector core to support their growth.
