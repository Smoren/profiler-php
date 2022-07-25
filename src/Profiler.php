<?php


namespace Smoren\Profiler;


/**
 * Profiler helper
 */
class Profiler
{
    protected static $process = [];
    protected static $statTime = [];
    protected static $statCalls = [];

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

        if(!isset(static::$statCalls[$name])) {
            static::$statCalls[$name] = 0;
        }

        static::$statCalls[$name]++;
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

        if(!isset(static::$statTime[$name])) {
            static::$statTime[$name] = 0;
        }
        static::$statTime[$name] += microtime(true) - static::$process[$name];
        unset(static::$process[$name]);
    }

    /**
     * @param string $name
     * @param callable $callback
     * @throws ProfilerException
     */
    public static function profile(string $name, callable $callback): void
    {
        static::start($name);
        $callback();
        static::stop($name);
    }

    /**
     * @return array
     */
    public static function getStatTime(): array
    {
        uasort(static::$statTime, function($lhs, $rhs) {
            if($rhs > $lhs) return 1;
            if($lhs < $rhs) return -1;
            return 0;
        });
        return static::$statTime;
    }

    /**
     * @return array
     */
    public static function getStatCalls(): array
    {
        uasort(static::$statCalls, function($lhs, $rhs) {
            return $rhs-$lhs;
        });
        return static::$statCalls;
    }

    /**
     * Clears all stat
     */
    public static function clear(): void
    {
        static::$process = [];
        static::$statTime = [];
        static::$statCalls = [];
    }
}
