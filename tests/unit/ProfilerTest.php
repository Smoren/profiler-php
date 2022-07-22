<?php

namespace Smoren\Profiler\Tests\Unit;

use Smoren\Profiler\Profiler;

class ProfilerTest extends \Codeception\Test\Unit
{
    public function testMain()
    {
        for($i=0; $i<10; ++$i) {
            $this->someTask();
        }

        $stat = Profiler::getStat();
        $this->assertEquals(0.1, round($stat['first'], 1));
        $this->assertEquals(0.2, round($stat['second'], 1));
    }

    protected function someTask()
    {
        Profiler::start('first');
        usleep(10000);
        Profiler::stop('first');

        Profiler::start('second');
        usleep(20000);
        Profiler::stop('second');
    }
}
