---
id: 77
title: "How to install old or new PHP on non-LTS Ubuntu"
perex: |
    Legacy projects can be upgraded in 2 directions - PHP-wise and infrastructure-wise. We can get a new Ubuntu 25 but still have to run old PHP.

    The same goes for upgrading a legacy project. Locally, we use Ubuntu 25, but we work on a project that uses PHP 7.2.

    By default, Ubuntu releases ship only one PHP version. Often we need one of the other ~10 versions. How do we run old or new PHP on the latest Ubuntu?
---

*This post extends [reply on Github issue](https://github.com/oerdnj/deb.sury.org/issues/1662#issuecomment-2823699313), to give it more visibility. Based on comments and SO struggles, many developers give up after reading too many misleading solutions:*

<img src="/assets/images/blog/2025/give-up.png" class="img-thumbnail">

*Don't fret. There is a way.*

<br>

*Disclaimer: do this cautiously, preferably on a local dev machine rather than production, to keep the risk of conflicts low.*

<br>

A typical solution is to add an external repository with all the old PHP versions.
This repository is maintained by [Ondrej Sury](https://github.com/sponsors/oerdnj), who has maintained it since the beginning of PHP times.

```bash
sudo add-apt-repository ppa:ondrej/php
sudo apt install ppa-purge
sudo ppa-purge ppa:ondrej/php
```

<br>

Then reload dependencies:

```bash
sudo apt-get update
sudo apt-get upgrade
```

<br>


And we can install our desired PHP version:

```bash
sudo apt-get install php7.4 php7.4-curl
```

This worked very well for years. Until 2021.

<br>

## Ubuntu non-LTS no longer supported

Maintaining these packages comes at a great cost. PHP is released once a year. Ubuntu has 2-3 releases a year, this adds to maintenance cost and time, adding to server costs.

We sponsored (PHP 8.1 release)[https://github.com/oerdnj/deb.sury.org/issues/1439#issuecomment-705552989] to help a bit:

<img src="/assets/images/blog/2025/rector-support.png" class="img-thumbnail">

But it wasn't enough. There is still [a way to sponsor Ondrej](https://github.com/sponsors/oerdnj) if you want to help out regularly.

This and probably other reasons lead to narrowing [support only to Ubuntu LTS releases](https://github.com/oerdnj/deb.sury.org/issues/1662).

<br>

That means only once every 2 years, without the x.10 improved version:

* 24.04 (released in 2024)
* 26.04 (will be in 2026)

<img src="/assets/images/blog/2025/ubuntu-releases.png" class="img-thumbnail">

<br>

## Downgrade to LTS or wait 2 years?

If you're working on an upgrade, downgrading the server would be a bit counterproductive. In the same way, using old PHP and waiting for 2 years to use another outdated PHP version would not help.

So how do we get the PHP version we want without being limited by the LTS Ubuntu release cycle?



## Fake it till you make it

If we try to install e.g. PHP 8.2 on Ubuntu 25, we get the following error:

```bash
sudo apt-get install php8.2
E: Package 'php-82' has no installation candidate
```

<br>

We already added the repository above, but still get errors.

* That's because there is no package list to **match our Ubuntu 25 codename - "plucky"** (it's the first word of the release name, lowercase).
* Yet, there is a package list that matches the LTS Ubuntu 24.04 codename - "noble".

<br>

Let's pretend we use the LTS Ubuntu 24.04:

```bash
# edit sources file
nano /etc/apt/sources.list.d/ondrej-ubuntu-php-plucky.sources
```

<br>

Change our codename:

```diff
-suites: x
+suites: noble
```

Save, and update packages:

```bash
sudo apt-get update
```

<br>

Now, let's give it a try:

```bash
sudo apt-get install php8.2

...

Get:1 https://ppa.launchpadcontent.net/ondrej/php/ubuntu noble/main amd64 php8.2-amqp amd64 2.1.2-5+ubuntu24.04.1+deb.sury.org+1 [59.1 kB]
Selecting previously unselected package php8.2.
(Reading database ... 276942 files and directories currently installed.)
Preparing to unpack .../php8.2.1.2-5+ubuntu24.04.1+deb.sury.org+1_amd64.deb ...
Unpacking php8.2 (2.1.2-5+ubuntu24.04.1+deb.sury.org+1) ...
Setting up php8.2 (2.1.2-5+ubuntu24.04.1+deb.sury.org+1) ...
```

Package installs successfully, yay!

That's it. Now we have PHP 8.2 installed on our local Ubuntu 25. Once PHP 8.5 is out, we can it too.

<br>

Happy coding!
