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
        }catch (PDOExeption $e){
            die($e->getMessage());
        }
       
    }
    /**
     * Get Database Connection Object PDO Object
     * 
     * @return PDO
     */
    public function connection()
    {
        return static::$connection;
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