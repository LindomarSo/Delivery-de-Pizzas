<?php

use App\Http\Response;
use App\Controller\Api;

$obRouter->post('/api/v1/auth',[
    'middlewares'=>[
        'api'
    ],
    function($request){
        return new Response(200,Api\Auth::generateToken($request),'application/json');
    }
]);