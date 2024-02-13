Sometimes the Rector crashes on exception and we need more detailed output to fix it or report a bug.

You can use `--debug` option, that will print nested exceptions output:

```bash
vendor/bin/rector process src/Controller --debug
```

Also an Xdebug:

1. Make sure [Xdebug](https://xdebug.org/) is installed and enabled
2. Add `--xdebug` option when running Rector

```bash
vendor/bin/rector process src/Controller --dry-run --xdebug
```

<br>

## Reporting a Bug with Demo

The best way to report a bug is then find the smallest possible `rector.php` config and single file in your project that causes the crash. Do not copy-paste the whole file, it would be miss leading. The crash usually happens on a 5-10 lines of code.

When you identify those, use https://getrector.com/demo to create a reproducer.

Then you can create an an issue or even a tests. Just click on the button on the right under the code.
