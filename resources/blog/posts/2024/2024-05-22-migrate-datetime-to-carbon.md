---
id: 66
title: "Migrate DateTime to Carbon"
perex: |
    Carbon is a great library for working with dates and times in PHP. It's being used [by Laravel](https://medium.com/@mhmmdtech/datetime-handling-in-laravel-by-carbon-39e032a15a15) as default date time library.

    But it's not only syntax sugar wrap around `DateTime` class. It brings reliable way to test your code that depends on exact dates and times.
---

The [Carbon package](https://github.com/briannesbitt/Carbon) brings a practical API to work with dates:

```php
// the DateTime
$date = (new \DateTime('today +20 day'))->format('Y-m-d');
```

```php
// the Carbon way
$date = \Carbon\Carbon::today()->addDays(20)->format('Y-m-d')
```

<br>

No need to remember strings like "+days", "-weeks", or "a month". You can use the method API directly:

```php
$date = \Carbon\Carbon::now()->addMonths(2);
```

## Reliable Tests Under Control

Where the Carbon package brings real value? Tests that depends on exact date. Native `DateTime` depends on timezone of server, or commiter. If they get into conflicts, "+1 day" can yield different result and make tests fail. Then we have to find out if that's false positive or a real problem. That's not the tests we want to debug.

Instead we can [mock the "now" directly](https://medium.com/@stefanledin/mock-date-and-time-with-carbon-8a9f72cb843d):

```php
// Don't really want this to happen so mock now
Carbon::setTestNow(Carbon::createFromDate(2000, 1, 1));

// comparisons are always done in UTC
if (Carbon::now()->gte($internetWillBlowUpOn)) {
    die();
}

// Phew! Return to normal behaviour
Carbon::setTestNow();
```

That way we  **make date constant through our test suite** for any developer or server.

## How to Migrate DateTime to Carbon

To make this work, we have to replace our `DateTime` instances with `Carbon` instances:

```diff
-$start = (new \DateTime('today +1 day'));
+$start = \Carbon\Carbon::today()->addDays(1);

-$end = (new \DateTime('today +30 days'));
+$end = \Carbon\Carbon::today()->addDays(30);
```

That's where Rector comes in. The next release will ship a new carbon set that will handle migration for you:

```php
<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPreparedSets(carbon: true);
```

This way your tests will become reliable and you won't have to wait for another developer to make CI trigger in the "right time" again.

<br>


This set is still fresh, so if it misses some case, [let us know in the issues](https://github.com/rectorphp/rector/issues).

<br>

Happy coding!
