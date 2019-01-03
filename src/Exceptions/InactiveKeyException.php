<?php

namespace Mettleworks\BigHugeThesaurusClient\Exceptions;

/**
 * Class InactiveKeyException
 * @package Mettleworks\BigHugeThesaurusClient\Exceptions
 */
class InactiveKeyException extends BigHugeThesaurusException
{
    const REASON = 'Inactive key';

    /**
     * UsageExceededException constructor.
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(string $message = '', int $code = 0, \Throwable $previous = null)
    {
        parent::__construct('The key is not active', 500, $previous);
    }
}
