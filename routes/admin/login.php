<?php

use \App\Controller\Admin;
use \App\Http\Response;

//ROTA DE LOGIN (GET)
$obRouter->get('/admin/login',[
    'middlewares'=>[
        'required-admin-logout'
    ],
    function($request){
        return new Response(200, Admin\Login::getLogin($request));
    }
]);

//ROTA DE LOGIN (POST)
$obRouter->post('/admin/login',[
    'middlewares'=>[
        'required-admin-logout'
    ],
    function($request){
        return new Response(200, Admin\Login::setLogin($request));
    }
]);

//ROTA DE LOGOUT 
$obRouter->get('/admin/logout',[
    'middlewares'=>[
        'required-admin-login'
    ],
    function($request){
        return new Response(200, Admin\Login::getLogout($request));
    }
]);