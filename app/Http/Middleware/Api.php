<?php

namespace App\Http\Middleware;

class Api 
{
    /**
     * Método responsável por executar o middleware
     * @param Request $request 
     * @param Closure $next 
     * @return Response
     */
    public function handle($request, $next){
        // ALTERA O CONTENT TYPE
        $request->getRouter()->setContentType('application/json');
    
        // CONTINUA A EXECUÇÃO
        return $next($request);
    }
}