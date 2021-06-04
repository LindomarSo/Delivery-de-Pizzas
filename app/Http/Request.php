<?php

namespace App\Http;

class Request
{
    /**
     * Route Instance
     * @var Route
     */
    private $route;

    /**
     * Http Method
     * @var string
     */
    private $httpMethod;

    /**
     * URI  of the page
     * @var string
     */
    private $uri;

    /**
     * Vaviables of the GET
     * @var array
     */
    private $queryParams = [];

    /**
     * Variables of the POST
     * @var array
     */
    private $postVars = [];

    /**
     * Headers of the request
     * @var array
     */
    private $headers = [];

    /**
     * Method responsible to start the class
     */
    public function __construct($route){
        $this->route = $route;
        $this->httpMethod = $_SERVER['REQUEST_METHOD'] ?? '';
        $this->uri = $_SERVER['REQUEST_URI'] ?? '';
        $this->queryParams = $_GET ?? '';
        $this->headers = getallheaders();
        $this->setPostVars();
    }

    /**
     * Método responsável por definir as variáveis do POST
     */
    private function setPostVars(){
        // VERIFICA O MÉTODO 
        if($this->httpMethod == 'GET') return false; 
        // POST PADRÃO
        $this->postVars = $_POST ?? '';
        // POST JSON
        $inputRaw = file_get_contents('php://input');
        $this->postVars = (strlen($inputRaw) && empty($_POST)) ? json_decode($inputRaw, true) : $this->postVars; 
    }

    /**
     * Método responsável por retornar a instância de router
     * @return Router
     */
    public function getRouter(){
        return $this->route;
    }

    /**
     * Method responsible to return Http method
     * @return string
     */
    public function getHttpMethod(){
        return $this->httpMethod;
    }

    /**
     * Method responsible to return URI
     * @return string
     */
    public function getUri(){
        // PEGAR A URI SEM OS GETS
        $xUri = explode('?', $this->uri);

        return $xUri[0];
    }

    /**
     * Method responsible to return query params
     * @return array
     */
    public function getQueryParams(){
        return $this->queryParams;
    }

    /**
     * Method responsible to return variables post
     * @return array
     */
    public function getPostVars(){
        return $this->postVars;
    }

    /**
     * Method responsible to return request headers
     * @return array
     */
    public function getHeaders(){
        return $this->headers;
    }
}