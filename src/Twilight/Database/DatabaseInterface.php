<?php

declare(strict_types=1);

namespace Twilight\Database;

use Twilight\Database\Exception\DatabaseException;
use PDO;

interface DatabaseInterface
{    
    /**
     * Opens the database connection
     *
     * @return PDO
     * @throws DatabaseException
     */
    public function open() : PDO;
    
    /**
     * Closes the database connection
     *
     * @return void
     */
    public function close();
}