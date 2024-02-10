You can use particular single rules, or whole list of rules, called "set lists":

```php
<?php

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPreparedSets(deadCode: true, codeQuality: true);
```

That way you can include group of rules that focus on certain topic, e.g. in this case on dead detection. It makes config small and clear.

<br>

## PHP Sets

How can you upgrade to the PHP 7.3?

```php
<?php

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPhpSets(php73: true);
```

That way you can use all the sets that are needed to upgrade your code to the desired PHP version in single line.


## External Sets

How can I use Rector with community sets or my custom one?

```php
<?php

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withSets([__DIR__ '/config/rector-custom-set.php']);
```
