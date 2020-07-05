<?php

namespace System;

class Application
{
    /**
     * container
     * 
     * @var array
     */
    private $container=[];
    /**
     * Application Object
     * 
     * @var \System\Application
     */
    private static $instance;
    /**
     * constructor
     * 
     * @param \System\File $file
     */
    private function __construct(File $file)
    {
        $this->share('file',$file);
        $this->registerClasses();
        
        $this->loadHelpers();

    }
    /**
     * Get Application Instance
     * 
     * @param \System\File $file
     * @return \System\Application
     */
    public static function getInstance($file = null)
    {
        if (is_null(static::$instance))
        {
            static::$instance= new static($file);
        }
        return static::$instance;
    }
    /**
     * Run the Application
     * 
     * @return void
     */
    public function run()
    {
        $this->session->start();

        $this->request->prepareUrl();

        $this->file->call('App/index.php');

        list($controller, $method, $arguments) = $this->route->getProperRoute();

        $output = (string) $this->load->action($controller, $method, $arguments);

        // if ($this->route->hasCallsFirst()) {
        //     $this->route->callFirstCalls();
        // }

        $this->response->setOutput($output);

        $this->response->send();
    }
    /**
     * Register classes in spl auto load register
     * 
     * @return void
     */
    private function registerClasses()
    {
        spl_autoload_register([$this, 'load']);
    }
    /**
     * load Class through autoloading
     * 
     * @param string $class
     * @return void
     */
    public function load($class)
    {
       if(strpos($class,'App')===0){
        $file= $class . '.php';
       }else{
           //get the class from vendor
           $file='vendor/' . $class . '.php';
           }
           if($this->file->exists($file)){
            $this->file->call($file);
       }
    }
    /**
     * load Helpers File
     * 
     * @return void
     */
    private function loadHelpers()
    {
        $this->file->call('vendor/helpers.php');
    }
    /**
     * Get share Value
     * 
     * @param string $key
     * @return mixed
     */
    public function get($key)
    {
        if(! $this->isSharing($key))
        {
            if($this->isCoreAlias($key))
            {
                $this->share($key,$this->createNewCoreObject($key));
            }else{
                die('<b>' . $key . '</b> not found in Application container');
            }
        }
            return $this->container[$key];
    }
    /**
     * Determine if the given key is shared through Application
     * 
     * @param string $key
     * @return bool
     */
    public function isSharing($key)
    {
        return isset($this->container[$key]);
    }
    /**
     * share the given key/value through Application
     * 
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    public function share($key, $value)
    {
        $this->container[$key]=$value;
    }
     /**
     * Determine if the given key is an alias to core class
     * 
     * @param string $alias
     * @return bool
     */
    private function isCoreAlias($alias)
    {
      $coreClasses=  $this->coreClasses();
      return isset($coreClasses[$alias]);
    }
    /**
     * creat new object for the core class based on the given alias
     * 
     * @param string $alias
     * @return object
     */
    private function createNewCoreObject($alias)
    {
        $coreClasses = $this->coreClasses();
        $object = $coreClasses[$alias];
        return new $object($this);
    }
    /**
     * Get All Core Classes with its aliases
     * 
     * @return array
     */
    private function coreClasses()
    {
       return[
            'request' => 'System\\Http\\Request',
            'response' => 'System\\Http\\Response',
            'session' => 'System\\Session',
            'route' => 'System\\Route',
            'cookie' => 'System\\Cookie',
            'load' => 'System\\Loader',
            'html' => 'System\\Html',
            'db' => 'System\\Database',
            'view' => 'System\\View\\ViewFactory',
            'url' => 'System\\Url',
            
       ];
    }
    /**
     * Get shared Value dynamically
     * 
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->get($key);
    }
}


?>