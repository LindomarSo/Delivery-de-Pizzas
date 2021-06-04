<?php

namespace App\Controller\Api;

class Api 
{
    /**
     * Método responsável pelos parâmetros default
     * @return array
     */
    public static function getDetails(){
        return [
            'nome'=>'API - Pizzaria',
            'Versao'=>'v1.0.0',
            'autor'=>'Lindomar',
            'email'=>'lindomardias@gmail.com'
        ];
    }

    /**
     * Método responsável por retornar detatlhes da paginação 
     * @param Request 
     * @param Pagination
     * @return array
     */
    protected static function getPagination($request, $obPagination){
        // BUSCA PÁGINAS 
        $pages = $obPagination->getPage();
        
        // QUERY PARAMS
        $queryParams = $request->getQueryParams();
        $paginaAtual = $queryParams['page'] ?? '';
        
        return [
            'paginaAtual'=>$paginaAtual ? (int)$paginaAtual : 1,
            'quantidadePaginas'=>!empty($pages) ? count($pages) : 1
        ];
    }
}