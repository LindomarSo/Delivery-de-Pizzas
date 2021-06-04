<?php

use App\Http\Response;
use App\Controller\Api;

$obRouter->get('/api/v1/users/me',[
    'middlewares'=>[
        'api',
        'jwt-auth'
    ],
    function($request){
        return new Response(200, Api\User::getCurrentUser($request), 'application/json');
    }
]);