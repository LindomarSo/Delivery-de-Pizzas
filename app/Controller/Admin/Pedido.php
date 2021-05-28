<?php

namespace App\Controller\Admin;

use App\Utils\View;

class Pedido extends Page
{
    /**
     * Método responsável por renderizar a view de pedido
     * @param Request $request 
     */
    public static function getPedido($request){
        $content = View::render('admin/modules/pedidos/pedidos',[]);

        return parent::getPanel('Pedidos', $content, 'pedidos');
    }
}