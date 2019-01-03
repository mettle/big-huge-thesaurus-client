<?php

namespace Mettleworks\BigHugeThesaurusClient\Exceptions;

/**
 * Class MissingWordsException
 * @package Mettleworks\BigHugeThesaurusClient\Exceptions
 */
class MissingWordsException extends BigHugeThesaurusException
{
    const REASON = 'Missing words';

    /**
     * MissingWordsException constructor.
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(string $message = '', int $code = 0, \Throwable $previous = null)
    {
        parent::__construct('No word was submitted', 500, $previous);
    }
}
