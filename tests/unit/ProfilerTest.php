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

        Profiler::profile('first', function() {
            usleep(200000);
        });

        $statTime = Profiler::getStatTime();
        $this->assertEquals(0.3, round($statTime['first'], 1));
        $this->assertEquals(0.2, round($statTime['second'], 1));

        $statCalls = Profiler::getStatCalls();
        $this->assertEquals(11, $statCalls['first']);
        $this->assertEquals(10, $statCalls['second']);
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
