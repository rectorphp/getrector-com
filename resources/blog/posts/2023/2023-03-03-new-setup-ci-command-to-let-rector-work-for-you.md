---
id: 47
title: "New command to add Rector to your CI in seconds"
perex: |
    ...

since_rector: 0.15.20
---

We're working hard to make the devleoper experience as smooth as possible. The less steps to your first run and fully automation with Rector, the better.

In February, we added one tiny improvement to the first run. When you run Rector for the very first time:

```bash
vendor/bin/rector
```

...and you have no `rector.php` in your project, Rector will generate it for you. With suggested directories and first rule to kick-off.

<br>

We're taking this further and adding a new command, that will help both new-comers and seasonal Rector users. You often ask, "how to add Rector to CI to work for you?"

In the end, it's quote short Github Workflow file, but there are few traps on the way:

* it must run only for core developer, who have access to the repository = that way Rector can actually contribute
* it must not run on forks
* it must run only on pull-requests
* the Rector commit must re-trigger the CI Workflows, so you have its work verified
* it needs access to Github token, to have enough rights to contribute

## How to achieve this in simple way?

This used to be a cumbersome road. Now we turned it to single command that:

* generates `.github/workflows/rector.yaml`
* fills your repository name, to handle the scope when the Workflow runs and does not bother external contributors
* shows you direct 2 links to:
    * create a Token
    * add it as secret to the repository


## How to use it?

First, make sure you have the latest Rector `0.15.20`, and then run locally:

```bash
vendor/bin/rector setup-ci
```


This is how it works:

@todo gif


## The result? Rector comits boring work for you

@todo


@see https://github.com/rectorphp/rector-src/pull/3425/files


-----------------



But there were problems:

* external contriburos can see the fails, but would have to run recto rlocally !== not Rector lazy philosophy
* Rector should work for you, when it can
* at the moment, only Github workflwos is support - do you use another CI service? add your template ot Rector so you can add it more eaisly on your next projetc

This commands handles
https://github.com/rectorphp/rector-src/pull/3438#discussion_r1123420679

