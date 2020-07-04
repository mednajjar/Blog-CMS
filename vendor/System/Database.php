<?php

namespace System;

use PDO;
use PDOException;

class Database
{
    /**
     * Application object
     * 
     * @var \system\Application
     */
    private $app;
    /**
     * PDO Connection
     * 
     * @var \PDO
     */
    private static $connection;

    /**
     * Table Name
     * 
     * @var string
     */
    private $table;
    /**
     * Data Container
     * 
     * @var array
     */
    private $data = [];
    /**
     * Bindings Container
     * 
     * @var array
     */
    private $bindings = [];
    /**
     * Last insert id
     * 
     * @var int
     */
    private $lastId;
    /**
     * Wheres
     * 
     * @var array
     */
    private $wheres = [];
    /**
     * Selects
     * 
     * @var array
     */
    private $selects = [];
    /**
     * Limit
     * 
     * @var int
     */
    private $limit;
    /**
     * Offset
     * 
     * @var int
     */
    private $offset;
     /**
     * Total rows
     * 
     * @var int
     */
    private $rows = 0;
    /**
     * Joins
     * 
     * @var array
     */
    private $joins = [];
     /**
     * Order By
     * 
     * @array
     */
    private $orerBy = [];
    /**
     * Constructor
     * 
     * @param \System\Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;

        if(! $this->isConnected())
        {
            $this->connect();
        }
            
    }
    /**
     * Determine if there is any connection to database
     * 
     * @return bool
     */
    private function isConnected()
    {
        return static::$connection instanceof PDO;
    }
     /**
     * Connect to database
     * 
     * @return void
     */
    private function connect()
    {
        
        $connectionData = $this->app->file->call('config.php');

        extract($connectionData);
        
        try{
            static::$connection = new PDO('mysql:host=' . $server . ';dbname=' . $dbname, $dbuser, $dbpass);

            static::$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

            static::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            static::$connection->exec('SET NAMES utf8');

        }catch (PDOExeption $e){
            die($e->getMessage());
        }
       
    }
    /**
     * Get Database Connection Object PDO Object
     * 
     * @return \PDO
     */
    public function connection()
    {
        return static::$connection;
    }

    /**
     * Set select clause
     * 
     * @param string $select
     * @return $this
     */
    public function select($select)
    {
        $this->selects[] = $select;

        return $this;
    }

    /**
     * Set join clause
     * 
     * @param string $join
     * @return $this
     */
    public function join($join)
    {
        $this->joins[] = $join;

        return $this;
    }

     /**
     * Set Order By clause
     * 
     * @param string $column
     * @param string $sort
     * @return $this
     */
    public function orderBy($orderBy, $sort = 'ASC')
    {
        $this->orerBy = [$orderBy, $sort];

        return $this;
    }

     /**
     * Set limit and offset
     * 
     * @param int $limit
     * @param int $offset
     * @return $this
     */
    public function limit($limit, $offset = 0)
    {
        $this->limit = $limit;
        $this->offset = $offset;

        return $this;
    }

    /**
     * Fetch Table
     * This will return only one record
     * 
     * @param string $table
     * @return \stdClass | null
     */
    public function fetch($table = null)
    {
        if($table){
            $this->table($table);
        }
        $sql = $this->fetchStatement();

        $result = $this->query($sql, $this->bindings)->fetch();

        $this->reset();

        return $result;
    }
    /**
     * Fetch All records from Table
     * 
     * @param string $table
     * @return array
     */
    public function fetchAll($table = null)
    {
        if($table){
            $this->table($table);
        }
        $sql = $this->fetchStatement();

        $query = $this->query($sql, $this->bindings);

        $results = $query->fetchAll();

        $this->rows = $query->rowCount();

        $this->reset();

        return $results;
    }
    /**
     * Get total rows from last fetch all statement
     * 
     * @return int
     */
    public function rows()
    {
        return $this->rows;
    }
    /**
     * Prepare Select Statement
     *
     * @return String 
     */
    private function fetchStatement()
    {
        $sql='SELECT ';

        if($this->selects)
        {
            $sql .= implode(',' , $this->selects);
        }else{
            $sql .= '*';
        }
        $sql .= ' FROM ' . $this->table . ' ';

        if ($this->joins){
            $sql .= implode(' ' , $this->joins);
        }

        if ($this->wheres){
            $sql .= ' WHERE ' . implode(' ' , $this->wheres);
        }

        if ($this->limit){
            $sql .= ' LIMIT ' . $this->limit;
        }

        if ($this->offset){
            $sql .= ' OFFSET ' . $this->offset;
        }

        if ($this->orerBy){
            $sql .= 'ORDER BY ' . implode(' ', $this->orerBy);
        }

        return $sql;

    }
    /**
     * Set the table name
     * 
     * @param string $table
     * @return $this
     */
    public function table($table)
    {
        $this->table = $table;

        return $this;
    }
    /**
     * set the table name
     * 
     * param string $table
     * @return $this
     */
    public function from($table)
    {
        return $this->table($table);
    }
    /**
     * Delete caulse
     * 
     * @param string $table
     * @return $this
     */
    public function delete($table = null)
    {
        if($table){
            $this->table($table);
        }
        $sql = ' DELETE FROM ' . $this->table . ' ';

        if($this->wheres){
            $sql .= ' WHERE ' . implode(' ' ,$this->wheres);
        }

        $this->query($sql, $this->bindings);

        $this->reset();

        return $this;
    }
     /**
     * Set the Data that will be stored in database table
     * 
     * @param mixed $key
     * @param mixed $value
     * @return $this
     */
    public function data($key, $value= null)
    {
        if(is_array($key)){
            $this->data = array_merge($this->data, $key);
            $this->addToBindings($key);
        }else{
            $this->data[$key] = $value;
            $this->addToBindings($value);
        }
        
        return $this;
    }
    /**
     * Insert Data to database
     * 
     * @param string $table
     * @return $this
     */
    public function insert($table = null)
    {
        if($table){
            $this->table($table);
        }

        $sql = 'INSERT INTO ' . $this->table . ' SET ';

        $sql = $this->setFields();

        $this->query($sql, $this->bindings);

        $this->lastId = $this->connection()->lastInsertId();

        $this->reset();

        return $this;
    }
     /**
     * Update Data in database
     * 
     * @param string $table
     * @return $this
     */
    public function update($table = null)
    {
        if($table){
            $this->table($table);
        }

        $sql = 'UPDATE ' . $this->table . ' SET ';

        $sql .= $this->setFields();

        if($this->wheres){
            $sql .= ' WHERE ' . implode(' ' , $this->wheres);
        }

        $this->query($sql, $this->bindings);

        $this->reset();

        return $this;
    }
    /**
     * set the field for insert and update
     * 
     * @return string
     */
    private function setFields()
    {
        $sql = '';
        
        foreach (array_keys($this->data) as $key){
            $sql .= '`' . $key . '` = ? , ';
        }
        $sql = rtrim($sql, ', ');

        return $sql;
    }
    /**
     * Add New where clause
     * 
     * @return $this
     */
    public function where()
    {
        $bindings = func_get_args();

        $sql = array_shift($bindings);

        $this->addToBindings($bindings);

        $this->wheres[] = $sql;

        return $this;
    }
    /**
     * Execute the given sql statement
     * 
     * @return \PDOStatement
     */
    public function query()
    {
        $bindings = func_get_args();

        $sql = array_shift($bindings);

        if (count($bindings) == 1 AND is_array($bindings[0]))
        {
            $bindings = $bindings[0];
        }
        try{
              // $query = static::$connection->query($sql);

            $query = $this->connection()->prepare($sql);

            foreach($bindings as $key => $value)
            {
                $query->bindValue($key + 1, _e($value));
            }

            $query->execute();

            return $query;
            
        } catch (PDOException $e)
        {
            echo $sql;

            pre($this->bindings);

            die($e->getMessage());
        }
    }
    /**
     * Get the last insert id
     * 
     * @return int
     */
    public function lastId()
    {
        return $this->lastId;
    }
    /**
     * Add the given value to bindings
     * 
     * @param mixed $value
     * @return void
     */
    private function addToBindings($value)
    {
        if (is_array($value))
        {
            $this->bindings = array_merge($this->bindings, array_values($value));

        }else{
            $this->bindings[] = $value; 
        }
       
    }
    /**
     * Reset All Data
     * 
     * @return void
     */
    private function reset()
    {
        
      
        $this->limit = null;
        $this->table = null;
        $this->offset = null;
        $this->data =[];
        $this->joins = [];
        $this->wheres = [];
        $this->orerBy = [];
        $this->selects = [];
        $this->bindings =[];

    }
    /**
     * call shared application objects dynamically
     * 
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->app->get($key);
    }

}










?>