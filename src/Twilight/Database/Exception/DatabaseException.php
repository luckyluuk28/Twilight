<?php

declare(strict_types=1);

namespace Twilight\Database\Exception;

class DatabaseException extends \PDOException
{    
    /**
     * Constructor
     *
     * @param  mixed $message
     * @param  mixed $code
     * @return void
     */
    public function __construct(protected $message = null, protected $code = null)
    {

    }
}