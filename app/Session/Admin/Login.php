<?php

namespace App\Session\Admin;

class Login 
{
    /**
     * Método responsável por iniciar sessão
     */
    private static function init(){
        // VERIFICA SE A SESSÃO ESTÁ ATIVA 
        if(session_status() != PHP_SESSION_ACTIVE){
            session_start();
        }
    }

    /**
     * Método responsável por iniciar um sessão do usuário
     * @param User $obUser
     * @return boolean
     */
    public static function login($obUser){
        // INICIA A SESSÃO 
        self::init();
        // DEFINE A SESSÃO DO USUÁRIO 
        $_SESSION['admin']['usuario'] = [
            'id'=>$obUser->id,
            'nome'=>$obUser->nome,
            'email'=>$obUser->email
        ];

        return true;
    }

    /**
     * Método responsável por verificar se o usuário está logado 
     */
    public static function isLogado(){
        // INICIA A SESSÃO
        self::init();

        // RETORNA A VERIFICAÇÃO
        return isset($_SESSION['admin']['usuario']['id']);
    }

    /**
     * Método responsável por deslogar um usuário
     * @return boolean
     */
    public static function logout(){
        // VERIFICA A SESSÃO
        self::init();

        unset($_SESSION['admin']['usuario']);

        return true;
    }
}