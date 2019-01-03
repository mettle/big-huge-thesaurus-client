<?php

namespace Mettleworks\BigHugeThesaurusClient\Exceptions;

/**
 * Class NotFoundException
 * @package Mettleworks\BigHugeThesaurusClient\Exceptions
 */
class NotFoundException extends BigHugeThesaurusException
{
    /**
     * NotFoundException constructor.
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(string $message = '', int $code = 0, \Throwable $previous = null)
    {
        parent::__construct('No data could be found for the word or alternates', 404, $previous);
    }
}
