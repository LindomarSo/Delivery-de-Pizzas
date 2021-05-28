<?php

use \App\Controller\Pages;
use \App\Http\Response;

//ROTA DE HOME
$obRouter->get('/',[
    function(){
        return new Response(200, Pages\Home::getHome());
    }
]);

//EXEMPLO DE ROTA DINÂMICA 
$obRouter->get('/pagina/{idPagina}/{acao}',[
    function($idPagina, $acao){
        return new Response(200, 'pagina '.$idPagina.'-'.$acao);
    }
]);

//ROTA DE REQUISIÇÃO VIA POST
$obRouter->post('/',[
    function($request){
        return new Response(200, Pages\Home::insertPedio($request));
    }
]);

//ROTA DE SOBRE APENAS PARA TESTES
$obRouter->get('/sobre',[
    function(){
        return new Response(200, Pages\Home::getHome());
    }
]);