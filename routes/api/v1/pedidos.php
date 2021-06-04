<?php

use App\Http\Response;
use App\Controller\Api;

// ROTA API PEDIDOS
$obRouter->get('/api/v1/pedidos',[
    'middlewares'=>[
        'api',
        'user-basic-auth'
    ],
    function($request){
        return new Response(200,Api\Pedidos::getPedidos($request), 'application/json');
    }
]);

// ROTA OBTER UM PEDIDO PELO ID
$obRouter->get('/api/v1/pedidos/{id}',[
    'middlewares'=>[
        'api',
        'user-basic-auth'
    ],
    function($request, $id){
        return new Response(200,Api\Pedidos::getPedidoById($request,$id), 'application/json');
    }
]);

// ROTA REALIZAR UM PEDIDO POST API
$obRouter->post('/api/v1/pedidos',[
    'middlewares'=>[
        'api',
        'user-basic-auth'
    ],
    function($request){
        return new Response(200,Api\Pedidos::setPedido($request), 'application/json');
    }
]);

// ROTA DELETE PEDIDO VIA API
$obRouter->delete('/api/v1/pedidos/{id}',[
    'middlewares'=>[
        'api',
        'user-basic-auth'
    ],
    function($request,$id){
        return new Response(200,Api\Pedidos::setDeletePedido($request,$id), 'application/json');
    }
]);