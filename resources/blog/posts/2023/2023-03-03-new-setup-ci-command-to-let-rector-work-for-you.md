---
id: 47
title: "New command to add Rector to your CI in seconds"
perex: |
    We're working hard to make the developer experience as smooth as possible. The fewer steps to your first run and full automation with Rector, the better.

    In February, we added improvement for the first run. Now we **add a new command to set up Rector in your CI to work for you**.

since_rector: 0.15.21
---


When you run Rector for the very first time, it generates the `rector.php` config for you. It suggests directories and the first rule to kick off. Let's take this further.

## Little preview of what we'll talk about today

1) Does your PHPStan fail?

<img src="/assets/images/blog/2023/before_rector_fix.png" class="img-thumbnail">

<br>
<br>

2) No worries, just let Rector fix it:

<img src="/assets/images/blog/2023/rector_fix.png" class="img-thumbnail">

<br>
<br>

3) And soon, your CI is passing and ready for merge ✅

<img src="/assets/images/blog/2023/after_rector_fix.png" class="img-thumbnail">



<br>

## Rector working for you

You often ask, "how can I add Rector to CI to work for me?" In the end, the answer is a short Github Workflow file, but there are **few traps on the way**:

* it must run only for core developers, who have access to the repository = that way Rector can actually contribute
* it must not run on forks
* it must run only on pull-requests
* the Rector commit must re-trigger the CI Workflows so you have its work verified
* it needs access to the Github token to have enough rights to contribute

This road is pretty cumbersome, right?

## Introducing the `setup-ci` command

Now we turned it to single command that:

* generates `.github/workflows/rector.yaml`
* fills your repository name to run workflow only for core contributors
* shows you 2 links: to create a Github token and add the token as a repository secret

## How to use it?

First, make sure you have the latest Rector `0.15.21`, and then run locally:

```bash
vendor/bin/rector setup-ci
```

<img src="/assets/images/blog/2023/rector_setup_ci_part_01.gif" class="img-thumbnail">


## Follow instructions

* 1) generate the token
* 2) fill it in the right place

<br>

<img src="/assets/images/blog/2023/rector_setup_ci_part_02.gif" class="img-thumbnail">

<br>
<br>

Push a pull-request, make a mistake and see how Rector handles boring work for you.

<br>

Happy coding!
