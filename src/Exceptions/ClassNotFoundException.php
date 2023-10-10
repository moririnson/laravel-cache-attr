<?php

namespace Laravel\Cache\Exceptions;

/**
 * Class ClassNotFoundException
 */
final class ClassNotFoundException extends \Exception
{
    /**
     * @param string          $class
     * @param int             $code
     * @param \Exception|null $previous
     */
    public function __construct($class, $code = 500, \Exception $previous = null)
    {
        parent::__construct('Class not found at path: ' . $class, $code, $previous);
    }
}