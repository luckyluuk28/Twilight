<?php

declare(strict_types=1);

namespace Twilight\Database;

use Twilight\Database\Exception\DatabaseException;
use PDOException;
use PDO;

class Database implements DatabaseInterface
{
    protected PDO $databaseHandle;

    public function __construct(protected array $credentials)
    {

    }
    
    /**
     * @inheritDoc
     */
    public function open() : PDO
    {
        try {
            $params = [
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ];
            $this->databaseHandle = new PDO(
                $this->credentials['databaseHandle'],
                $this->credentials['username'],
                $this->credentials['password'],
                $params
            );
        } catch(PDOException $exception) {
            throw new DatabaseException($exception->getMessage(), (int)$exception->getCode());
        }

        return $this->databaseHandle;
    }
    
    /**
     * @inheritDoc
     */
    public function close()
    { 
        $this->databaseHandle = null;
    }
}

