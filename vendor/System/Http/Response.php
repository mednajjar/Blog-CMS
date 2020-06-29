<?php

namespace System\Http;

use System\Application;

class Response
{
    /**
     * Application Object
     * 
     * @var \System\Application
     */
    private $app;
    /**
     * headers container that will be sent to the browser
     * 
     * @var array
     */
    private $headers= [];
    /**
     * the content that will be sent to the browser
     * 
     * @var string
     */
    private $content= '';
    /**
     * Constructor
     * 
     * @param \system\Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }
    /**
     * set the response output content
     * 
     * @param string $content
     * @return void
     */
    public function setOutput($content)
    {
        $this->content = $content;
    }
    /**
     * Set the response Headers
     * 
     * @param string $header
     * @param mixed value
     * @return void
     */
    public function setHeader($header, $value)
    {
        $this->headers[$header] = $value;
    }
    /**
     * send the response headers and content
     * 
     * @return void
     */
    public function send()
    {
        $this->sendHeaders();

        $this->sendOutput();
    }
    /**
     * send the response headers
     * 
     * @return void
     */
    private function sendHeaders()
    {
        foreach ($this->headers as $header => $value)
        {
            header($header . ':' . $value);
        }
    }
    /**
     * Send the response output
     * 
     * @return void
     */
    private function sendOutput()
    {
        echo $this->content;
    }


}
















?>