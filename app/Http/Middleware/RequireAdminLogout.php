<?php

namespace App\Http\Middleware;

use App\Session\Admin\Login as SessionAdminLogin;

class RequireAdminLogout 
{
    /**
     * Método responsável por executar o middleware de logout
     * @param Request $request 
     * @param Closure $next 
     * @return Response
     */
    public function handle($request, $next){
        // VERIFICA SE O USUÁRIO ESTÁ LOGADO
        if(SessionAdminLogin::isLogado()){
            $request->getRouter()->redirect('/admin');
        }

        // CONTINUA A EXECUÇÃO
        return $next($request);
    }
}