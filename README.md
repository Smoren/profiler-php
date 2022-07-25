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

Profiler::profile('third', function() {
    usleep(30000);
});

print_r(Profiler::getStatTime());
/*
Array
(
    [second] => 0.2015209197998
    [third] => 0.20024418830872
    [first] => 0.10135746002197
)
*/

print_r(Profiler::getStatCalls());
/*
Array
(
    [first] => 10
    [second] => 10
    [third] => 1
)
*/
```
