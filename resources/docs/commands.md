Rector has one main CLI command to process files. There are few less known, but useful commands that you might use in different situations:


## 1. Show loaded rules

Are you curious, how many rules are running in your project? Run:

```bash
vendor/bin/rector list-rules
```

You'll see list of all rules that are being run and skipped in your config.

```bash
vendor/bin/rector list-rules --config config/other-rector.php
```

Do you want to pipe result into next tool? Get it in JSON:

```bash
vendor/bin/rector list-rules --output-format json
```

## 2. Setup CI script

Do you use Github Actions or Gitlab CI? Rector can generate a CI script for you:

```bash
vendor/bin/rector setup-ci
```

It will generate `.github/workflows/rector.yaml` or `.gitlab-ci.yml` file with Rector run that works for you. Fill you credentials and Rector will work for you on CI.


## 3. Generate Custom Rule

Do you want to create a custom rule? Rector can generate a basic structure for you:

```bash
vendor/bin/rector custom-rule
```

Just fill your rule name and Rector generates a basic structure for you, including psr-4 paths in `composer.json`. Don't forget to dump autoload, to enable the new psr-4 paths:

```bash
composer dump-autoload
```
