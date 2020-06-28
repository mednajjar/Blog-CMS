<?php

namespace System;

class Route
{
    /**
     * Application Object
     * 
     * @var \System\Application
     */
    private $app;
    /**
     * Route Container
     * 
     * @var array
     */
    private $routes=[];
    /**
     * Not Found url
     * 
     * @var string
     */
    private $notFound;
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
     * Add New Route
     * 
     * @param string $url
     * @param string $action
     * @param string $requestMethod
     * @param void
     */
    public function add($url, $action, $requestMethod = 'GET')
    {
        $route=[
            'url' => $url,
            'pattern' => $this->generatePattern($url),
            'action' => $this->getAction($action),
            'method' => $requestMethod,

        ];
        $this->routes[]=$route;
    }
    /**
     * Set Not Found Url
     * 
     * @param string $url
     * @return void
     */
    public function notFound($url)
    {
        $this->notFound = $url;
    }
    /**
     * Get Proper Route
     * 
     * @return array
     */
    public function getProperRoute()
    {
        foreach($this->routes as $route){
            if($this->isMatching($route['pattern'])){
                $arguments = $this->getArgumentsFrom($route['pattern']);
                // controller@method
                list($controller, $method) = explode('@', $route['action']);
                return [$controller, $method, $arguments];
            }
        }
    }
    /**
     * Determine if the given pattern matches the current request url
     * 
     * @param string $pattern
     * @return bool
     */
    private function isMatching($pattern)
    {
        return preg_match($pattern, $this->app->request->url());
    }
    /**
     * Get Arguments from the current request url based on the given pattern
     * 
     * @param string $pattern
     * @return array
     */
    private function getArgumentsFrom($pattern)
    {
        preg_match($pattern, $this->app->request->url(), $matches);
        array_shift($matches);
        return $matches;
    }
    /**
     * Generate a regex pattern for the given url
     * 
     * @param string $url
     * @return string
     */
    private function generatePattern($url)
    {
        $pattern = '#^';
        // :text ([a-zA-Z0-9-]+)
        // :id (\decimal+)
        // example str_replace : my name is khan
        // change 'my' by 'your'
        // str_replace('my','your','my name is khan');

        $pattern .= str_replace([':text',':id'],['([a-zA-Z0-9-]+)', '(\d+)'], $url);
        $pattern .= '$#';
        return $pattern;
    }
    /**
     * Get the Proper Action
     * 
     * @param string $action
     * @return string
     */
    private function getAction($action)
    {
        $action = str_replace('/', '\\', $action);
        return strpos($action, '@') !== false ? $action : $action . '@index';
    }
}


?>