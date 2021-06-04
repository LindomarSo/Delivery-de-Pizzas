<?php

namespace App\Http;

use Closure;
use Exception;
use ReflectionFunction;
use App\Http\Middleware\Queue;

class Router 
{
    /**
     * Full URL of the page
     * @var string
     */
    private $url;

    /**
     * Prefix all of routes
     * @var string
     */
    private $prefix = '';

    /**
     * receive the routes 
     * @var array
     */
    private $routes = [];

    /**
     * Request instance
     * @var Request
     */
    private $request;

    /**
     * Content Type
     * @var string
     */
    private $contentType = 'text/html';

    /**
     * Method responsible to start the class
     */
    public function __construct($url){
        $this->request = new Request($this);
        $this->url = $url;
        $this->setPrefix();
    }

    /**
     * Método responsável por alterar o content type
     * @param string $message
     */
    public function setContentType($message){
        $this->contentType = $message;
    }

    /**
     * Method responsible for to set prefix
     * @return string
     */
    public function setPrefix(){
       $prefix = parse_url($this->url);
    
       $this->prefix = $prefix['path'] ?? '';
    }

    /**
     * Method responsible for add a new route
     * @param string $method 
     * @param string $route
     * @param array $params
     */
    private function addRoute($method, $route, $params = []){
        // VALIDATE THE PARAMS 
        foreach($params as $key => $value){
            if($value instanceof Closure){
                $params['controller'] = $value;
                unset($params[$key]);
                continue;
            }
        }

        // MIDDLEWARES DA ROTA 
        $params['middlewares'] = $params['middlewares'] ?? [];

        // VARIÁVEIS DA ROTA
        $params['variables'] = [];
       
        // PADRÃO DE VALIDAÇÃO DE VARIÁVEIS DAS ROTAS
        $patternVariable = '/{(.*?)}/';

        if(preg_match_all($patternVariable, $route, $matches)){
            $route  = preg_replace($patternVariable, '(.*?)', $route);
            $params['variables'] = $matches[1];
        }
    
        $patternRoute = '/^'.str_replace('/','\/',$route).'$/';
        $this->routes[$patternRoute][$method] = $params;
    }

    /**
     * GET route
     * @param string $route 
     * @param array $params
     */
    public function get($route, $params = []){
        return $this->addRoute('GET', $route, $params);
    }

    /**
     * POST route
     * @param string 
     * @param array
     */
    public function post($route, $params = []){
        return $this->addRoute('POST',$route, $params);
    }

    /**
     * Delete route
     * @param string 
     * @param array
     */
    public function delete($route, $params = []){
        return $this->addRoute('DELETE',$route, $params);
    }

    /**
     * Method responsible for to get the URI
     * @return string
     */
    private function getUri(){
        // URI OF REQUEST
        $uri = $this->request->getUri();

        $xUri = strlen($this->prefix) ? explode($this->prefix, $uri) : [$uri];

        // RETURN URI WITHOUT PREFIX
        return end($xUri);
    }

    /**
     * Method responsible for to get the route
     */
    public function getRoute(){
        // GET URI
        $uri = $this->getUri();

        $httpMethod = $this->request->getHttpMethod();
       
       foreach($this->routes as $patternRoute => $method){
           // CHECK IF WITHOUT METHOD
           if(preg_match($patternRoute, $uri, $matches)){
            
                if(isset($method[$httpMethod])){
                    unset($matches[0]);
                    // VARIÁVEIS PROCESSADAS
                    $keys = $method[$httpMethod]['variables'];
                    $method[$httpMethod]['variables'] = array_combine($keys, $matches);
                    $method[$httpMethod]['variables']['request'] = $this->request;

                    return $method[$httpMethod];
                }

                throw new Exception("Método não permitido", 405);
            }
       }

       throw new Exception("URL Não encontrada", 404);
    }

    /**
     * Method responsible for to execute current route
     * @return Response
     */
    public function run(){
        try{
            $route = $this->getRoute();
            
           if(!isset($route['controller'])){
                return throw new Exception("A URL não pode ser processada!", 500);
           }

           // ARGUMENTOS DA FUNÇÃO
           $args = [];

           $reflection = new ReflectionFunction($route['controller']);
           foreach($reflection->getParameters() as $parameter){
               $name = $parameter->getName();
               $args[$name] = $route['variables'][$name] ?? '';
           }

           // RETORNA TUDO PARA AS QUEUE
           return (new Queue($route['controller'], $route['middlewares'], $args))->next($this->request);

        }catch(Exception $e){
            return new Response($e->getCode(), $this->getMessageError($e->getMessage()),$this->contentType);
        }
    }

    /**
     *Método responsável por pegar a mensagem de erro
     */
    public function getMessageError($message){
        switch($this->contentType){
            case 'application/json':
                return [
                    'error'=>$message
                ];
                break;
            default:
                return $message;
                break;    
        }
    }

    /**
     * Método responsável por pegar a url sem os gets
     * @return string
     */
    public function getUrl(){
        return $this->url.$this->getUri();
    }

    /**
     * Método responsável por redirecionar a página
     */
    public function redirect($route){
        $url = $this->url.$route;
      
        header('location: '.$url);

        exit;
    }
}