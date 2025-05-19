---
id: 79
title: "Introducing Rector Jack: Raise Your Dependencies Safely"
perex: |
    Hey PHP folks! 👋 We're thrilled to share **Jack**, a new experimental CLI tool to help you lift your Composer dependencies one version at a time &ndash; safely and steadily. If you've ever dreaded the "oh no, our dependencies are *years* old" moment, Jack's here to make upgrades less painful.

    We've tested it internally for a couple months, published silently to **pass 3500 downloads in 20 days**, it's time to share it with the world.

    It fits both legacy projects to reach higher faster, and modern projects not to fall behind.
---

<br>

## What's Jack All About?

<img src="https://github.com/rectorphp/jack/raw/main/docs/jack.jpg" class="img-thumbnail" style="max-width: 20em">

In the real world, a jack lifts your car to fix issues underneath. In the Composer world, **Jack** lifts your dependency versions, so you can keep your project cruising smoothly. It's built to tackle the chaos of outdated packages with a simple, automated approach.

Why use Jack? Because manual upgrades are a headache. Big version jumps often bring errors, compatibility woes, and wasted time. Jack steps in to:

- Catch outdated dependencies in CI.
- Gradually open up package versions for safe updates.
- Prioritize low-risk changes (like dev dependencies).

It's **open-source** and built with community feedback in mind. We're developing this in the open, so your input shapes Jack's future!

<br>

## How Does It Work?

Jack is lightweight, works on **PHP 7.2+**, and slots into any legacy project. Install it with:

```bash
composer require rector/jack --dev
```

<br>

From there, you've got three killer commands:

<br>

### 1. Catch Too Many Outdated Dependencies in CI

Run `vendor/bin/jack breakpoint` to spot major outdated packages. If you've got more than 5, your CI will fail, keeping upgrades on your radar.

Tweak the limit with `--limit 3` or focus on dev packages with `--dev`.

<br>

### 2. Open Up Next Versions

Unsure where to start with upgrades? Let Jack nudge your `composer.json` to the next version (e.g., `symfony/console: 5.1.*` → `5.1.*|5.2.*`).

Just run:

```bash
vendor/bin/jack open-versions
```

It will open your current constraints in `composer.json` to the nearest next version. Then, `composer update` does the heavy lifting.

Want to play it safe?

* Use `--dev` to start with dev packages first,
* `--limit` to handle different number of packages
* or `--dry-run` for a preview.

<br>

### 3. Sync `composer.json` with Already Installed Versions

Got a modern project but an outdated `composer.json`? Jack raises your dependency versions to match what's installed:

```bash
vendor/bin/jack raise-to-installed
```

No more signaling old versions you don't even use!

<br>

## Try Jack and Share Your Thoughts

Jack is experimental, so we're counting on you to kick the tires. Try it out, report bugs, or suggest features on [GitHub](https://github.com/rectorphp/jack). Want to see it in action? Check the [README](https://github.com/rectorphp/jack#readme) for more examples.

<br>

We're excited to turn boring dependency upgrades to more fun game.

<br>

Happy coding! 🚀

