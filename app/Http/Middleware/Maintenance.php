<?php

namespace App\Http\Middleware;

class Maintenance
{
    /**
     * Método responsável por executar o middleware de manutenção
     * @param Request
     * @param Closure
     * @return Response
     */
    public function handle($request, $next){
        //VERIFICA O ESTADO DE MUNUTENÇÃO DA PÁGINA 
        if(getenv('MAINTENANCE') == 'true'){
            throw new \Exception("Página em manuteção. Tente novamente mais tarde!", 200);
        }

        //EXECUTA O PROXIMO MIDDLEWARE 
        return $next($request);
    }
}