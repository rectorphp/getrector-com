## Provide Config

By default, Rector picks `rector.php` in your root directory as a configuration file. To change that, use `--config` option in CLI run:

```bash
vendor/bin/rector process --config rector-custom-config.php
```

## Spacing and Indents

By default, Rector prints code with 4 spaces indent and unix newline.
If you have other preference, change it:

```php
<?php

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withIndent(indentChar: ' ', indentSize: 4);
```
