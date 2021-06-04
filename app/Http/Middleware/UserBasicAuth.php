<?php

namespace App\Http\Middleware;

use App\Model\Entity\User;

class UserBasicAuth 
{
    /**
     * Método responsável por busucar um usuário no banco 
     * @return User
     */
    private function getBasicAuth(){
        // BUSCAR USUÁRIO NO BANCO 
        $obUser = User::getUserByEmail($_SERVER['PHP_AUTH_USER']);
        
        if(!$obUser instanceof User){
            return false;
        }

        return password_verify($_SERVER['PHP_AUTH_PW'], $obUser->senha) ? $obUser : false; 
    }

    /**
     * Método responsável por fazer a vailidação do acesso HTTP BASIC AUTH
     * @param $request 
     */
    private function basicAuth($request){
       // VERIFICA O USUÁRIO RECEBIDO
       if($obUser = $this->getBasicAuth()){
            $obUser->user = $obUser;
            return true;
       }

       throw new \Exception("Usuário ou senha inválido!", 403);
    }

    /**
     * Método responsável por executar o middleware
     * @param Request $request 
     * @param Closure $next 
     * @return Response
     */
    public function handle($request, $next){
        // REALIZA A VALIDAÇÃO DO ACESSO VIA BASIC AUTH
        $this->basicAuth($request);
        // CONTINUA A EXECUÇÃO 
        return $next($request);
    }
}