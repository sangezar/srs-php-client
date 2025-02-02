<?php

namespace SrsClient\Exception;

use Exception;

/**
 * Exception thrown when SRS API errors occur
 *
 * @package SrsClient\Exception
 */
class SrsApiException extends \Exception
{
    /**
     * @param string $message Error message
     * @param int $code Error code
     * @param Exception|null $previous Previous exception
     */
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
} 