<?php

namespace App\Controller\Api;

use App\Model\Entity\User;
use Firebase\JWT\JWT;

class Auth 
{
    /**
     * Método responsável por gerar token
     * @param Request $request 
     * @return array
     */
    public static function generateToken($request){
        // POST VARS 
        $postVars  = $request->getPostVars();
       
        // VALIDA OS CAMPOS 
        if(!isset($postVars['email']) or !isset($postVars['senha'])){
            throw new \Exception("Os campos email e senha são obrigatórios!",400);
        }

        // BUSCA O USUÁRIO 
        $obUser = User::getUserByEmail($postVars['email']);
        
        // VERIFICA A INSTÂNCIA 
        if(!$obUser instanceof User or !password_verify($postVars['senha'], $obUser->senha)){
            throw new \Exception("Usuário ou senha inválidos!",400); 
        }

        // PAYLOAD
        $payload = [
            'email'=>$obUser->email
        ];
        
        return [
            'token'=> JWT::encode($payload, getenv('JWT_KEY'))
        ];
    }
}