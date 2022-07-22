<?php


namespace Smoren\Profiler;


/**
 * Profiler helper
 */
class Profiler
{
    protected static $process = [];
    protected static $stat = [];

    /**
     * @param string $name
     * @throws ProfilerException
     */
    public static function start(string $name): void
    {
        if(isset(static::$process[$name])) {
            throw new ProfilerException(
                "process '{$name}' already started",
                ProfilerException::STATUS_PROCESS_ALREADY_STARTED,
                null,
                ['name' => $name]
            );
        }
        static::$process[$name] = microtime(true);
    }

    /**
     * @param string $name
     * @throws ProfilerException
     */
    public static function stop(string $name): void
    {
        if(!isset(static::$process[$name])) {
            throw new ProfilerException(
                "process '{$name}' not started yet",
                ProfilerException::STATUS_PROCESS_NOT_STARTED_YET,
                null,
                ['name' => $name]
            );
        }

        if(!isset(static::$stat[$name])) {
            static::$stat[$name] = 0;
        }
        static::$stat[$name] += microtime(true) - static::$process[$name];
        unset(static::$process[$name]);
    }

    /**
     * @return array
     */
    public static function getStat(): array
    {
        return static::$stat;
    }

    public static function printStat(): void
    {
        print_r(static::$stat);
    }
}
