<?php

use App\Http\Response;
use App\Controller\Api;

$obRouter->get('/api/v1',[
    'middlewares'=>[
        'api'
    ],
    function(){
        return new Response(200,Api\Api::getDetails(), 'application/json');
    }
]);