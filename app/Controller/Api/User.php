<?php

namespace App\Controller\Api;

class User 
{
    /**
     * Método responsável por pegar o usuário  atual
     * @param Request $request 
     * @return array
     */
    public static function getCurrentUser($request){
        // BUSCA O USUÁRIO ATUAL 
        $usuario = $request->user;
        
        return [
            'id'=>$usuario->id,
            'nome'=>$usuario->nome,
            'email'=>$usuario->email
        ];
    }
}