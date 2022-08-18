# profiler

![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/smoren/profiler)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Smoren/profiler-php/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Smoren/profiler-php/?branch=master)
[![Coverage Status](https://coveralls.io/repos/github/Smoren/profiler-php/badge.svg?branch=master)](https://coveralls.io/github/Smoren/profiler-php?branch=master)
![Build and test](https://github.com/Smoren/profiler-php/actions/workflows/test_master.yml/badge.svg)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

Profiler helper

### How to install to your project
```
composer require smoren/profiler
```

### Unit testing
```
composer install
composer test-init
composer test
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
