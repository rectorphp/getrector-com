---
id: 25
title: "Dropping Docker in Favor of Composer Install for Better Developer Experience"
perex: |
    Some developers see Docker as default-to-use for Rector. Yet they struggle to run it successfully with fundamental changes like [renaming class from underscore to namespace](https://twitter.com/frankdejonge/status/1419298126133927941).

    It's very frustrating for them, and they often end-up up deleting the tool with a bad feeling inside.

    This cannot happen.
---

The core team around Rector is not using Docker for running because there is no need for it. The current Docker version is naturally obsolete and lacking behind PHP code development.

## Use Rector like PHPUnit

**With [downgrades](https://getrector.com/blog/2021/03/22/rector-010-released-with-php71-support) and [prefixed release](https://getrector.com/blog/prefixed-rector-by-default) repository, Rector can be easily installed on any project:**

```bash
composer require rector/rector --dev
```

Rector can save you hundreds of hours on automated refactoring and instant upgrades. But the main added features are in the long-term feedback and everyday work it resolves for you. With new rules and sets, you should never upgrade manually ever again. How?

The same way PHPUnit checks your tests in CI, [the same way Rector works in CI for you](https://getrector.com/blog/2020/10/05/how-to-make-rector-contribute-your-pull-requests-every-day).

<br>

If the PHP version is the limit, Docker will not help as Rector requires PHP 7.1 to run. There is [PHP 5.6 sponsorware program](https://getrector.com/blog/rector-for-php56-native) for projects who want to make it happen.

<br>

Saying that, we're dropping [Docker from native Rector support](https://github.com/rectorphp/rector-src/pull/614). Do you want to keep Docker for Rector alive? See [the issue](https://github.com/rectorphp/rector-src/pull/614) to propose community maintenance.

<br>

We believe this choice will radically improve experience for first time users and embrace focus on single codebase to make it strong and reliable.

<br>

Thank you and happy coding!
