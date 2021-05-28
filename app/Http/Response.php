<?php

namespace App\Http;

class Response 
{
    /**
     * Status Code 
     * @var integer
     */
    private $httpCode = 200;

    /**
     * Headers of response
     * @var array
     */
    private $headers = [];

    /**
     * Content Type of the page
     * @var string
     */
    private $contentType = 'text/html';

    /**
     * Content of the page
     * @var string
     */
    private $content;

    /**
     * Construct method of the class
     * @param integer $httpCode
     * @param string $content
     * @param string $contentType
     */
    public function __construct($httpCode, $content, $contentType = 'text/html'){
        $this->httpCode = $httpCode;
        $this->content = $content;
        $this->setContentType($contentType);
    }

    /**
     * Methdo responsible for update contentType
     * @return string
     */
    private function setContentType($contentType){
        $this->setHeaders('Content-Type',$contentType);
        return $this->contentType = $contentType;
    }

    /**
     * Method responsible for update all headers
     * @return array
     */
    private function setHeaders($key, $value){
        return $this->headers[$key] = $value;
    }

    /**
     * Method responsible for the send the headers
     */
    private function sendHeaders(){
        // STATUS CODE 
        http_response_code($this->httpCode);

        foreach($this->headers as $key => $value){
            header($key.': '.$value);
        }
    }

    /**
     * Method responsible for to send the content
     */
    public function sendResponse(){
        // SEND THE HEADERS 
        $this->sendHeaders();
        // PRINT THE CONTENT
        switch($this->contentType){
            case 'text/html';
                echo $this->content;
                exit;
        }
    }
}