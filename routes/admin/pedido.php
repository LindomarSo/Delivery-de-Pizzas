<?php

use \App\Controller\Admin;
use \App\Http\Response;

//ROTA DE PEDIDOS (GET)
$obRouter->get('/admin/pedidos',[
    'middlewares'=>[
        'required-admin-login'
    ],
    function($request){
        return new Response(200, Admin\Pedido::getPedido($request));
    }
]);

//ROTA DE PEDIDOS (POST)
$obRouter->post('/admin/pedidos',[
    'middlewares'=>[
        'required-admin-login'
    ],
    function($request){
        return new Response(200, Admin\Pedido::setMixPedido($request));
    }
]);