<?php

use \App\Controller\Admin;
use \App\Http\Response;

//ROTA DE LOGIN (POST)
$obRouter->get('/admin/pedidos',[
    'middlewares'=>[
        'required-admin-login'
    ],
    function($request){
        return new Response(200, Admin\Pedido::getPedido($request));
    }
]);