<?php

namespace App\Controller\Api;

use App\Model\Entity\Pedido as EntityPedido;
use App\Database\Pagination;

class pedidos extends Api
{
    /**
     * Método responsável pelos dados do pedido
     * @param Request $request 
     * @return array
     */
    public static function getPedidos($request){
        // PEDIDOS
        $pedidos = [];
        // OBTÉM TODOS OS PEDIDOS
        $quantidadeTotal = EntityPedido::getAsks(null, null, null, 'COUNT(*) as qtd')
                                        ->fetchObject()->qtd;
        // QUERY PARAMS
        $queryParams = $request->getQueryParams();
        $paginaAtual = $queryParams['page'] ?? '';

        // INSTÂNCIA DE PAGINATION
        $obPagination = new Pagination($quantidadeTotal, $paginaAtual, 2);

        // RESULTADOS DA PÁGINA
        $obPedido = EntityPedido::getAsks(null, 'id ASC',$obPagination->getLimit());
        
        while($result = $obPedido->fetchObject(EntityPedido::class)){
            // PEGA AS PIZZAS
            $pizza = self::getPizza($result->pizzas_id);
            // PEGA OS PIZZA_SABOR
            $pizza_sabor = self::getPizzaSabor($result->pizzas_id);
            
            $pedidos[] = [
                'pedido'=>$result->pizzas_id,
                'borda'=>self::getBorda($pizza->bordas_id),
                'massa'=>self::getMassa($pizza->massas_id),
                'sabores'=>$pizza_sabor,
                'status'=>self::setStatus($result->status_id)
            ];
        }
        
        return [
            'pedidos'=> $pedidos,
            'paginacao'=>parent::getPagination($request,$obPagination)
        ];   
        
    }

    /**
     * Método responsável por resgatar a borda da pizza
     * @param integer $id
     * @return integer
     */
    private static function getBorda($id){
        // OBTÉM UMA BORDA PELO ID 
        $obBorda = \App\Model\Entity\Bordas::getBorda($id);
        
        return $obBorda->tipo;
    }

    /**
     * Método responsável por buscar o tipo de massa
     * @param integer $id 
     * @return string
     */
    private static function getMassa($id){
        // OBTÉM UMA MASSA PELO ID
        $obMassa = \App\Model\Entity\Massas::getMassaById($id);

        return $obMassa->tipo;
    }

     /**
     * Método responsável por pegar as pizzas
     * @param integer $id
     * @return Pizza
     */
    private static function getPizza($id){
        // OBTÉM UMA PIZZA PELO ID 
        $obPizza = \App\Model\Entity\Pizza::getPizzaById($id);

        return $obPizza;
    }

    private static function getPizzaSabor($id){
        // OBTÉM UM A TABELA DE RELACIONAMENTO
        $obPizzaSabor = \App\Model\Entity\PizzaSabor::getPizzaSaborById($id);
        $sabores = [];
        foreach($obPizzaSabor as $sabor){
            // OBTÉM O NOME DOS SABORES
            $obSabores = \App\Model\Entity\Sabores::getSaborById($sabor['sabores_id']);
            
            $sabores[] = [
                'sabores'=>$obSabores->nome
            ];
        }
       return $sabores;
    }

    /**
     * Método responsável por atualizar o estatus de produção
     */
    private static function setStatus($status_id){
        // OBTÉM TODOS OS STATUS
        $obStatus = \App\Model\Entity\Status::getStatus();
        $status = [];
        while($result = $obStatus->fetchObject(\App\Model\Entity\Status::class)){
            $status[] = [
                'status'=>$result->tipo,
                'id'=>$result->id,  
                'item'=> $status_id == $result->id ? 'selected' : ''
            ];
        }

        return $status;
    }

    /**
     * Método responsável por obter um pedido pelo ID
     * @param Request $request 
     * @param integer $id 
     * @return array
     */
    public static function getPedidoById($request, $id){
        // VALIDA O ID
        if(!is_numeric($id)){
            throw new \Exception("O id não é válido!", 404);
        }
       // BUSCA UM PEDIDO PELO ID
       $obPedido = new EntityPedido;
       $obPedido->pizzas_id = $id;
       $obPedido = $obPedido->getPedidoById();
       
       // VALIDA A INSTÂNCA
       if(!$obPedido instanceof EntityPedido){
           throw new \Exception("Pedido não encontrado!",400);
       }

       // PEGA AS PIZZAS
       $pizza = self::getPizza($obPedido->pizzas_id);
       // PEGA OS PIZZA_SABOR
       $pizza_sabor = self::getPizzaSabor($obPedido->pizzas_id);
       
       $pedido[] = [
           'pedido'=>$obPedido->pizzas_id,
           'borda'=>self::getBorda($pizza->bordas_id),
           'massa'=>self::getMassa($pizza->massas_id),
           'sabores'=>$pizza_sabor,
           'status'=>self::setStatus($obPedido->status_id)
       ];

       return $pedido;
    }

    /**
     * Método responsável por realizar um pedido
     * @param Request $request 
     */
    public static function setPedido($request){
        // POST VARS
        $postVars = $request->getPostVars();
        // INSTÂNCIA DE PIZZAS
        $obPizza = new \App\Model\Entity\Pizza;
        //INSTÂNCIA DE SABORES
        $obSabores = new \App\Model\Entity\PizzaSabor;
        $borda = isset($postVars['borda']) ? $postVars['borda'] : '';
        $massa = isset($postVars['massa']) ? $postVars['massa'] : '';
        $sabores = isset($postVars['sabores']) ? $postVars['sabores'] : '';
       
        if($borda == '' or $massa == '' or $sabores == ''){
            throw new \Exception("Campos vazios não são permitidos", 400);
        }
        if(count($sabores) > 3){
            throw new \Exception("São permitidos apenas 3 sabores!", 400);
        }
        
        $obPizza->bordas_id = $borda;
        $obPizza->massas_id = $massa;
        $obSabores->sabores_id = $sabores;
        $obPizza->cadastrarPizza($request);
        return [
           'borda'=>self::getBorda($obPizza->bordas_id),
           'massa'=>self::getMassa($obPizza->massas_id),
           'sabores'=>$obSabores
        ];
    }

    /**
     * Método responsável por deletar um pedido
     * @param Request $request 
     * @param integer $id 
     */
    public static function setDeletePedido($request, $id){
        // VALIDA O ID
        if(!is_numeric($id)){
            throw new \Exception("O id ".$id." não é válido!", 404);
        }
       // BUSCA UM PEDIDO PELO ID
       $obPedido = new EntityPedido;
       $obPedido->pizzas_id = $id;
       $obPedido = $obPedido->getPedidoById();
       
       // VALIDA A INSTÂNCA
       if(!$obPedido instanceof EntityPedido){
           throw new \Exception("Pedido não encontrado!",400);
       }

       // DELETA O PEDIDO
       $obPedido->excluir();
       
        return [
           'sucesso'=>true
        ];
    }
}