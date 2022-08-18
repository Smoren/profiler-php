<?php

namespace Smoren\Profiler\Tests\Unit;

use Smoren\Profiler\Profiler;
use Smoren\Profiler\ProfilerException;

class ProfilerTest extends \Codeception\Test\Unit
{
    /**
     * @throws ProfilerException
     */
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

        Profiler::clear();
        $this->assertCount(0, Profiler::getStatTime());
    }

    /**
     * @throws ProfilerException
     */
    public function testErrors()
    {
        $processName = 'some_process_name';
        
        try {
            Profiler::stop($processName);
            $this->assertTrue(false);
        } catch(ProfilerException $e) {
            $this->assertEquals(ProfilerException::STATUS_PROCESS_NOT_STARTED_YET, $e->getCode());
        }

        Profiler::start($processName);
        try {
            Profiler::start($processName);
            $this->assertTrue(false);
        } catch(ProfilerException $e) {
            $this->assertEquals(ProfilerException::STATUS_PROCESS_ALREADY_STARTED, $e->getCode());
        }
        Profiler::stop($processName);

        try {
            Profiler::stop($processName);
            $this->assertTrue(false);
        } catch(ProfilerException $e) {
            $this->assertEquals(ProfilerException::STATUS_PROCESS_NOT_STARTED_YET, $e->getCode());
        }
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
