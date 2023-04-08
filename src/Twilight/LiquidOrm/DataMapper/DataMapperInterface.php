<?php

declare(strict_types=1);

namespace Twilight\LiquidOrm\DataMapper;

use Twilight\DataMapper\Exception\DataMapperException;

interface DataMapperInterface
{    
    /**
     * Prepare query string (protects against SQLInjection)
     *
     * @param  string $query
     * @return self
     */
    public function prepare(string $query) : self;
    
    /**
     * Set datatype using PDO::PARAM constants 
     *
     * @param  mixed $var
     * @return 
     */
    public function bind(mixed $var);
     
    /**
     * Binds a value to a corresponding name or question mark placeholder in the SQL
     * statement that was used to prepare the statement
     *
     * @param  mixed $fields
     * @param  mixed $isSearch
     * @return self | false
     * @throws DataMapperException
     */
    public function bindParameters(array $fields, bool $isSearch = false) : self | false;
    
    /**
     * Execute the prepared statement
     *
     * @return
     */
    public function execute();
    
    /**
     * Returns the number of rows affected
     *
     * @return int|null
     */
    public function numRows() : int | null;
    
    /**
     * Return a single result
     *
     * @return object
     */
    public function result() : object | null;
    
    /**
     * Return all rows of the result
     *
     * @return array
     */
    public function results() : array | null;
    
    /**
     * Returns last inserted row Id from the database
     *
     * @return int|null
     * @throws \Throwable
     */
    public function getLastId() : int | null;
}