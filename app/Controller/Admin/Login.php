<?php

namespace App\Controller\Admin;

use App\Utils\View;
use App\Model\Entity\User as EntityUser;
use App\Session\Admin\Login as SessioLogin;

class Login extends Page
{
    /**
     * Método responsável por carregar a rota de get login
     * @param Request $request
     * @return string
     */
    public static function getLogin($request, $messageError = null){
        $status = !is_null($messageError) ? View::render('alert/alert',[
            'status'=>$messageError,
            'field'=>'alert-danger'
        ]) : '';
        $content = View::render('admin/login',[
            'status'=>$status
        ]);

        return parent::getPage('Login', $content);
    }

    /**
     * Método responsável por definir o login do usuário
     * @param Request $request 
     */
    public static function setLogin($request){
        // VARIÁVEIS DO POST
        $postVars = $request->getPostVars();
        $senha = $postVars['senha'] ?? '';
        $email = $postVars['email'] ?? '';
        
        // INSTÂNCIA DE USER ENTITY
        $obUser =  EntityUser::getUserByEmail($email);

        // VALIDA A INSTÂNCIA
        if(!$obUser instanceof EntityUser){
            return self::getLogin($request, 'E-mail ou senha inválidos');
        }
        
        // VERIFICA A SENHA E USUÁRIO
        if(!password_verify($senha, $obUser->senha)){
            return self::getLogin($request, 'E-mail ou senha iválidos');
        }

        // CRIA A SESSÃO DO USUÁRIO
        SessioLogin::login($obUser);
        
        $request->getRouter()->redirect('/admin');
    }

    /**
     * Método responsável por deslogar um usuário 
     */
    public static function getLogout($request){
        // DESTROI A SESSÃO DE LOGIN
        SessioLogin::logout();

        $request->getRouter()->redirect('/admin/login');
    }
}