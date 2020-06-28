<?php

namespace System\Http;

class Request
{
    /**
     * Url
     * 
     * @var string
     */
    private $url;
    /**
     * Base Url
     * 
     * @var string
     */
    private $baseUrl;
    /**
     * Prepare url
     * 
     * @return void
     */
    public function prepareUrl()
    {
        $script = dirname($this->server('SCRIPT_NAME'));
        $requestUri = $this->server('REQUEST_URI');
       
        if(strpos($requestUri, '?') !== false){
            list($requestUri, $queryString)= explode('?', $requestUri);
        }
        $this->url = preg_replace('#^'.$script.'#', '' , $requestUri);
        
        $this->baseUrl=$this->server('REQUEST_SCHEME') . '://' . $this->server('HTTP_HOST') . $script . '/';
        
    }
    /**
     * Get value from _GET by the given key
     * 
     * @param string $key
     * @param mexed $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return array_get($_GET, $key, $default);
    }
    /**
     * Get value from _POST by the given key
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function post($key, $default = null)
    {
        return array_get($_POST, $key, $default);
    }
    /**
     * Get value from _SERVER by the given key
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function server($key, $default = null)
    {
        return array_get($_SERVER, $key, $default);
    }
    /**
     * Get Current Request Method
     * 
     * @return string
     */
    public function methode()
    {
        return $this->server('REQUEST_METHOD');
    }
    /**
     * Get full url of the script
     * 
     * @return string
     */

    public function baseUrl()
    {
        return $this->baseUrl;
    }
    /**
     * Get only relative url (clean url)
     * 
     * @return string
     */
    public function url()
    {
        return $this->url;
    }
}

?>