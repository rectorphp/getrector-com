---
id: 77
title: "How to install old or new PHP on non-LTS Ubuntu"
perex: |
    Legacy projects can be upgraded in 2 directions - PHP-wise and infrastructure-wise. We can get new Ubuntu 25, but still have to run old PHP.

    Same goes with upgrading a legacy project. Locally, we use Ubuntu 25, but we work on a project that uses PHP 7.2.

    By default, Ubuntu release ship only one PHP version. Often we need one of the other ~10 versions. How do we get run old or new PHP on latest Ubuntu?
---

*This post extends [reply on Github issue](https://github.com/oerdnj/deb.sury.org/issues/1662#issuecomment-2823699313), to give it more visibility. Based on comments and SO struggles, many developers give up after reading to many miss-leading solutions. There is a way.*

<br>

*Disclaimer: do this with caution, preferably on a local dev machine rather than production, to keep risk of conflicts low.*

<br>

Typical solution is to add an external repository with all the old PHP version.
This repository is maintained by [Ondrej Sury](https://github.com/sponsors/oerdnj), who maintains it since the beginning of PHP times.

```bash
sudo add-apt-repository ppa:ondrej/php
sudo apt install ppa-purge
sudo ppa-purge ppa:ondrej/php
```

Then reload dependencies:

```bash
sudo apt-get update
sudo apt-get upgrade
```

And we can install our desired PHP version:

```bash
sudo apt-get install php7.4 php7.4-curl
```

This worked very well for years. Until 2021.

<br>

## Ubuntu non-LTS no longer supported

Maintaining these packages comes at great cost. PHP has release once a year. Ubuntu has 2-3 release a year, this adds to maintenance cost and time, adding to server costs.

We sponsored (PHP 8.1 release)[https://github.com/oerdnj/deb.sury.org/issues/1439#issuecomment-705552989] to help a bit:

<img src="/assets/images/blog/2025/rector-support.png" class="img-thumbnail">

But it wasn't enough. There is still [way to sponsor Ondrej](https://github.com/sponsors/oerdnj), if you want to help out regularly.

This and probably other reason lead to narrowing [support only to Ubuntu LTS releases](https://github.com/oerdnj/deb.sury.org/issues/1662).

That means only once every 2 years, without the x.10 improved version:

* 24.04 (released in 2024)
* 26.04 (will be in 2026)

<img src="/assets/images/blog/2025/ubuntu-releases.png" class="img-thumbnail">

<br>

## Downgrade to LTS or wait 2 years?

If you're working on an upgrade, downgrading the server would be a bit counter productive. The same way, using old PHP and waiting for 2 year to use another outdated PHP version would not help.

So how do we get the PHP version we want without being limited by LTS Ubuntu release cycle?



## Fake it till you make it

If we try to install e.g. PHP 8.2 on Ubuntu 25, we get following error:

```bash
sudo apt-get install php8.2
E: Package 'php-82' has no installation candidate
```

We already added the repository above, but still get error.

That's because there is no package list to match our Ubuntu 25 codename - "plucky" (it's the first word of the relaase name, lowercased).

Yet, there is a package list that matches the LTS Ubuntu 24.04 codename - "noble".

<br>

Let's pretend we use old Ubuntu 24.04:

```bash
# edit sources file
nano /etc/apt/sources.list.d/ondrej-ubuntu-php-plucky.sources
```

Here we change our codename:

```diff
-suites: x
+suites: noble
```

Save, update packages:

```
sudo apt-get update
```

Now, let's give it a try:

```bash
sudo apt-get install php8.2
```

Package installs successfully:

```bash
...
Get:1 https://ppa.launchpadcontent.net/ondrej/php/ubuntu noble/main amd64 php8.2-amqp amd64 2.1.2-5+ubuntu24.04.1+deb.sury.org+1 [59.1 kB]
Selecting previously unselected package php8.2.
(Reading database ... 276942 files and directories currently installed.)
Preparing to unpack .../php8.2.1.2-5+ubuntu24.04.1+deb.sury.org+1_amd64.deb ...
Unpacking php8.2 (2.1.2-5+ubuntu24.04.1+deb.sury.org+1) ...
Setting up php8.2 (2.1.2-5+ubuntu24.04.1+deb.sury.org+1) ...
```

That's it. Now we have PHP 8.2 installed on our local Ubuntu 25.

<br>

Happy coding!
