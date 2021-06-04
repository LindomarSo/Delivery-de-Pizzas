<?php

namespace App\Http\Middleware;

use App\Model\Entity\User;
use Firebase\JWT\JWT;

class JWTAuth
{
    /**
     * Método responsável por buscar um usuário 
     * @param Request $request
     * @return User
     */
    private function getJWTAuthUser($request){
        // HEADERS
        $headers = $request->getHeaders();
        // TOKEN PURO EM JWT
        $jwt = isset($headers['Authorization']) ? str_replace('Bearer ','', $headers['Authorization']) : '';
        
        try{
            $decode = (array)JWT::decode($jwt, getenv('JWT_KEY'),['HS256']);
        }catch(\Exception $e){
            throw new \Exception("Token inválido!", 403);
        }

        // EMAIL
        $email = $decode['email'] ?? '';

        // BUSCA EMAIL PELO USUÁRIO 
        $obUser =  User::getUserByEmail($email);

        return $obUser instanceof User ? $obUser : false;
    }

    /**
     * Método responsável 
     * @param Request $request
     */
    private function auth($request){
        // VALIDA O USUÁRIO 
        if($obUser = $this->getJWTAuthUser($request)){
            $request->user = $obUser;
            return true;
        }

        throw new \Exception("Acesso negado!", 403);
    }

    /**
     * Método responsável por executar o middleware
     * @param Request $request 
     * @param Closure $next 
     * @return Response
     */
    public function handle($request, $next){
        $this->auth($request);

        // CONTINUA A EXECUÇÃO
        return $next($request);
    }
}