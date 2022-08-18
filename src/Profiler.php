<?php

namespace Smoren\Profiler;

/**
 * Profiler helper
 */
class Profiler
{
    /**
     * @var array<string, float>
     */
    protected static $processMap = [];
    /**
     * @var array<string, float>
     */
    protected static $statTimeMap = [];
    /**
     * @var array<string, int>
     */
    protected static $statCallsCountMap = [];

    /**
     * Starts the profiling of named process
     * @param string $name process name
     * @throws ProfilerException
     */
    public static function start(string $name): void
    {
        if(isset(static::$processMap[$name])) {
            throw new ProfilerException(
                "process '{$name}' already started",
                ProfilerException::STATUS_PROCESS_ALREADY_STARTED,
                null,
                ['name' => $name]
            );
        }
        static::$processMap[$name] = microtime(true);

        if(!isset(static::$statCallsCountMap[$name])) {
            static::$statCallsCountMap[$name] = 0;
        }

        static::$statCallsCountMap[$name]++;
    }

    /**
     * Stops the profiling of named process
     * @param string $name process name
     * @throws ProfilerException
     */
    public static function stop(string $name): void
    {
        if(!isset(static::$processMap[$name])) {
            throw new ProfilerException(
                "process '{$name}' not started yet",
                ProfilerException::STATUS_PROCESS_NOT_STARTED_YET,
                null,
                ['name' => $name]
            );
        }

        if(!isset(static::$statTimeMap[$name])) {
            static::$statTimeMap[$name] = 0;
        }
        static::$statTimeMap[$name] += microtime(true) - static::$processMap[$name];
        unset(static::$processMap[$name]);
    }

    /**
     * Profiles the body of callback function
     * @param string $name process name
     * @param callable $callback function with body to profile
     * @throws ProfilerException
     */
    public static function profile(string $name, callable $callback): void
    {
        static::start($name);
        $callback();
        static::stop($name);
    }

    /**
     * Returns time usage summary
     * @return array<string, float>
     */
    public static function getStatTime(): array
    {
        uasort(static::$statTimeMap, function($lhs, $rhs) {
            if($rhs > $lhs) {
                return 1;
            }
            if($lhs < $rhs) {
                return -1;
            }
            return 0;
        });
        return static::$statTimeMap;
    }

    /**
     * Returns calls counters summary
     * @return array<string, int>
     */
    public static function getStatCalls(): array
    {
        uasort(static::$statCallsCountMap, function($lhs, $rhs) {
            return $rhs-$lhs;
        });
        return static::$statCallsCountMap;
    }

    /**
     * Clears all stat
     */
    public static function clear(): void
    {
        static::$processMap = [];
        static::$statTimeMap = [];
        static::$statCallsCountMap = [];
    }
}
