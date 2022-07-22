# profiler

Profiler helper

### How to install to your project
```
composer require smoren/profiler
```

### Unit testing
```
composer install
./vendor/bin/codecept build
./vendor/bin/codecept run unit tests/unit
```

### Usage

```php
use Smoren\Profiler\Profiler;

function someTask()
{
    Profiler::start('first');
    usleep(10000);
    Profiler::stop('first');

    Profiler::start('second');
    usleep(20000);
    Profiler::stop('second');
}

for($i=0; $i<10; ++$i) {
    someTask();
}

print_r(Profiler::getStat());
/*
 Array
(
    [first] => 0.10141491889954
    [second] => 0.20137596130371
)
*/
```
