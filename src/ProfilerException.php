<?php


namespace Smoren\Profiler;


use Smoren\ExtendedExceptions\BaseException;

class ProfilerException extends BaseException
{
    const STATUS_PROCESS_ALREADY_STARTED = 1;
    const STATUS_PROCESS_NOT_STARTED_YET = 2;
}
