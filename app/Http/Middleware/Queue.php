<?php

namespace App\Http\Middleware;

use Closure;

class Queue 
{
    /**
     * Mapear os middlwares 
     */
    private static $map = [];

    /**
     * Middlewares padrões de todoas as rotas
     * @var array
     */
    private static $default = [];

    /**
     * Função de execução do Controllador 
     * @var Closure
     */
    private $controller;

    /**
     * Fila de middlewares
     * @var array
     */
    private $middlewares = [];

    /**
     * Argumentos da função 
     * @var array
     */
    private $controllerArgs = [];

    /**
     * Método responsável por iniciar a class
     * @param Closure $controller
     * @param array $middlewares
     * @param array $controllerArgs
     */
    public function __construct($controller, $middlewares, $controllerArgs){
        $this->controller = $controller;
        $this->middlewares = array_merge(self::$default,$middlewares);
        $this->controllerArgs = $controllerArgs;
    }

    /**
     * Função responsável por fazer o mapeamento dos middlewares
     * @param array $map
     */
    public static function setMap($map){
        self::$map = $map;
    }

    /**
     * Método responsável por definir o middleware padrão
     * @param array
     */
    public static function setDefault($default){
        self::$default = $default;
    }

    /**
     * Método responsável por exeucutar a fila de middlewares
     * @param Request
     */
    public function next($request){
        // VERIFICA SE EXISTE MIDDLEWARE
        if(empty($this->middlewares)){
            return call_user_func_array($this->controller,$this->controllerArgs);
        }
      
        // MIDDLEWARE
        $middleware = array_shift($this->middlewares);
       
        //VERIFICA SE EXISTE O MIDDLEWARE
        if(!isset(self::$map[$middleware])){
            throw new \Exception("Problemas ao processar o middleware", 500);
        }
        
        $queue = $this;
        $next = function($request) use ($queue){
            return $queue->next($request);
        };

        return (new self::$map[$middleware])->handle($request, $next);
    }
}