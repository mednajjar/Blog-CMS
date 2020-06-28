<?php

namespace System;

abstract class Controller
{
    /**
     * Application object
     * 
     * @var \system\Application
     */
    private $app;
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

}




?>