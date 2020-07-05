<?php

namespace System;

abstract class Model
{
    /**
     * Application object
     * 
     * @var \system\Application
     */
    protected $app;
    /**
     * Table name
     * 
     * @var string
     */
    protected $table;
    /**
     * Constructor
     * 
     * @param \System\Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
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
    /**
     * Get all Model records
     * 
     * @return array
     */
    public function all()
    {
        return $this->fetchAll($this->table);
    }
    /**
     * Get record By Id
     * 
     * @param int $id
     * @return \stdClass | null
     */
    public function get($id)
    {
        return $this->where('id = ?' , $id)->fetch($this->table);
    }
    /**
     * Call Database methods dynamically
     * 
     * @param string $method
     * @param array $args
     * @return mixed
     */
    public function __call($method, $args)
    {
        return call_user_func_array([$this->app->db, $method], $args);
    }

}




?>