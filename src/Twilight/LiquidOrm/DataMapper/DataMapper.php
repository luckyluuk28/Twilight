<?php

declare(strict_types=1);

namespace Twilight\LiquidOrm\DataMapper;

use Twilight\Database\DatabaseInterface;
use Twilight\DataMapper\Exception\DataMapperException;
use Throwable;
use PDO;
use PDOStatement;

class DataMapper implements DataMapperInterface
{
    private PDOStatement $statement;
    
    /**
     * Main constructor
     *
     * @param  DatabaseInterface $databaseHandle
     * @return void
     */
    public function __construct(private DatabaseInterface $databaseHandle) { }
        
    /**
     * Checks if value is empty and throws exception if it is
     *
     * @param  mixed $value
     * @param  string|null $errorMessage
     * @return void
     * @throws DataMapperException
     */
    private function isEmpty($value, string $errorMessage = null) : void
    {
        if (empty($value))
            throw new DataMapperException($errorMessage);
    }
    
    /**
     * Checks if value is an array and throws exception if it's not
     *
     * @param  array $value
     * @return void
     * @throws DataMapperException
     */
    private function isArray(array $value) : void
    {
        if (!is_array($value))
            throw new DataMapperException('The argument needs to be an array');
    }

    /**
     * @inheritDoc
     */
    public function prepare(string $query) : self
    {
        $this->statement = $this->databaseHandle->open()->prepare($query);
        return $this;
    }
    
    /**
     * @inheritDoc
     */
    public function bind(mixed $var)
    {
        try {
            switch($var) {
                case is_bool($var) :
                case intval($var) :
                    $dataType = PDO::PARAM_INT;
                    break;
                case is_null($var) :
                    $dataType = PDO::PARAM_NULL;
                    break;
                default :
                    $dataType = PDO::PARAM_STR;
                    break;
            }
            return $dataType;
        } catch(DataMapperException $exception) {
            throw $exception;
        }
    }
    
    /**
     * @inheritDoc
     */
    public function bindParameters(array $fields, bool $isSearch = false) : self | false
    {
        $this->isArray($fields);
        if (is_array($fields)) {
            $type = ($isSearch === false) ? $this->bindValue($fields) : $this->bindSearchValues($fields);
            if ($type) return $this;
        }
        return false;
    }
        
    /**
     * bindValue
     *
     * @param  mixed $fields
     * @return PDOStatement
     * @throws DataMapperException
     */
    protected function bindValue(array $fields) : PDOStatement {
        foreach ($fields as $key => $value) {
            $this->statement->bindValue(':' . $key, $value, $this->bind($value));
        }
        return $this->statement;
    }

    /**
     * Binds a value to a corresponding name or question mark placeholder
     * in the SQL statement that was used to prepare the statement. Similar to
     * above but optimised for search queries
     * 
     * @param array $fields
     * @return PDOStatement
     * @throws \Exception
     */
    protected function bindSearchValues(array $fields) :  PDOStatement
    {
        foreach ($fields as $key => $value) {
            $this->statement->bindValue(':' . $key,  '%' . $value . '%', $this->bind($value));
        }
        return $this->statement;
    }
    
    /**
     * @inheritDoc
     */
    public function execute()
    {
        if ($this->statement) return $this->statement->execute();
    }
    
    /**
     * @inheritDoc
     */
    public function numRows() : int | null
    {
        if ($this->statement) return $this->statement->rowCount();
        return null;
    }
    
    /**
     * @inheritDoc
     */
    public function result() : object | null
    {
        if ($this->statement) return $this->statement->fetch(PDO::FETCH_OBJ);
        return null;
    }
    
    /**
     * @inheritDoc
     */
    public function results() : array | null
    {
        if ($this->statement) return $this->statement->fetchAll();
        return null;
    }
    
    /**
     * @inheritDoc
     */
    public function getLastId() : int | null
    {
        try {
            if ($this->databaseHandle->open()) {
                $lastId = $this->databaseHandle->open()->lastInsertId();
                if (!empty($lastId)) return intval($lastId);
            }
        } catch(Throwable $throwable) {
            throw $throwable;
        }
        return null;
    }
}