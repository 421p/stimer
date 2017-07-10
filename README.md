## Stimer, a timer with pause/resume feature.

And immediately proceed to the samples:

Regular timer:

```php
$loop = Factory::create();
$interval = 5;

$timer = new Timer($loop, $interval, function () { echo 'hello world'; });

// ... 3 seconds

$timer->pause();
$timer->getLeftInterval(); // ~ 2 seconds

// ... something happens

$timer->resume();

// ... 2 seconds

// hello world

```

Periodic timer:

```php
$loop = Factory::create();
$interval = 5;

$timer = new PeriodicTimer($loop, $interval, function () { echo 'hello world'; });

// ... 3 seconds

$timer->pause();
$timer->getLeftInterval(); // ~ 2 seconds

// ... something happens

$timer->resume();

// ... 2 seconds

// hello world

// ... 5 seconds

// hello world

```
