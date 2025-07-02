On some dependency with CLI command, eg: PHPUnit 12+, you may got error:

```bash
PHP Fatal error:  Cannot declare interface PhpParser\NodeVisitor, because the name is already in use in /home/runner/work/rector-rules/rector-rules/vendor/rector/rector/vendor/nikic/php-parser/lib/PhpParser/NodeVisitor.php on line 6
```

which its own autoload too early loaded, you may need to handle it with register `rector` preload.php under `"autoload-dev" -> "files"` like below:

```json
{
    "autoload-dev": {
        "psr-4": {
            "Your\\Test\\": "tests/"
        },
        "files": [
            "vendor/rector/rector/preload.php"
        ]
    }
}
```

then, run:

```bash
composer update
```

and verify it resolved.
