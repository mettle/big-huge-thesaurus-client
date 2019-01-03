<?php

namespace Mettleworks\BigHugeThesaurusClient\Exceptions;

/**
 * Class NotWhitelistedException
 * @package Mettleworks\BigHugeThesaurusClient\Exceptions
 */
class NotWhitelistedException extends BigHugeThesaurusException
{
    const REASON = 'Not whitelisted';

    /**
     * NotWhitelistedException constructor.
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(string $message = '', int $code = 0, \Throwable $previous = null)
    {
        parent::__construct('The IP address was blocked', 500, $previous);
    }
}
