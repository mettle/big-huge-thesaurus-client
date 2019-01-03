<?php

namespace Mettleworks\BigHugeThesaurusClient\Exceptions;

/**
 * Class UsageExceededException
 * @package Mettleworks\BigHugeThesaurusClient\Exceptions
 */
class UsageExceededException extends BigHugeThesaurusException
{
    const REASON = 'Usage Exceeded';

    /**
     * UsageExceededException constructor.
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(string $message = '', int $code = 0, \Throwable $previous = null)
    {
        parent::__construct('Usage limits have been exceeded', 500, $previous);
    }
}
