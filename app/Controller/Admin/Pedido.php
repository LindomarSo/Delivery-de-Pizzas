<?php

namespace App\Controller\Admin;

use App\Utils\View;
use App\Model\Entity\Bordas;
use App\Model\Entity\Massas;
use App\Model\Entity\Pedido as EntityPedido;
use App\Model\Entity\Pizza;
use App\Model\Entity\PizzaSabor;
use App\Model\Entity\Sabores;
use App\Model\Entity\Status;
use App\Database\Pagination;

class Pedido extends Page
{
    /**
     * Método responsável por renderizar a paginação
     * @param Request $request 
     * @param Pagination $obPagination
     * @return string  
     */
    private static function getPagination($request, $obPagination){
        // LINKS
        $links = '';
        // PEGA URL
        $url = $request->getRouter()->getUrl();
      
        $queryParams = $request->getQueryParams();

        $paginas = $obPagination->getPage();

        foreach($paginas as $page){
            $queryParams['page'] = $page['paginas'];

            unset($queryParams['url']);

            $link = $url.'?'.http_build_query($queryParams);
            
            $links .= View::render('pagination/link',[
                'link'=>$link,
                'page'=>$page['paginas'],
                'active'=>$page['atual'] ? 'active' : ''
            ]);
        }

        return View::render('pagination/pagination',[
            'link'=>$links
        ]);
    }

     /**
     * Método responsável por renderizar a view de pedido
     * @param Request $request 
     */
    public static function getPedido($request){ 
        $content = View::render('admin/modules/pedidos/pedidos',[
            'itens'=>self::getAsk($request, $obPagination),
            'message'=>self::getStatus($request),
            'pagination'=>self::getPagination($request, $obPagination)
        ]);

        return parent::getPanel('Pedidos', $content, 'pedidos');
    }

    /**
     * Método responsável por pegar o pedido
     */
    private static function getAsk($request, &$obPagination){
        // PEDIDOS
        $pedidos = '';
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
            
            $pedidos .= View::render('admin/modules/pedidos/itens',[
                'pedido'=>$result->pizzas_id,
                'borda'=>self::getBorda($pizza->bordas_id),
                'massa'=>self::getMassa($pizza->massas_id),
                'sabores'=>$pizza_sabor,
                'status'=>self::setStatus($result->status_id),
                'id'=>$result->pizzas_id
            ]);
        }
        
        return $pedidos;
    }

    /**
     * Método responsável por resgatar a borda da pizza
     * @param integer $id
     * @return integer
     */
    private static function getBorda($id){
        // OBTÉM UMA BORDA PELO ID 
        $obBorda = Bordas::getBorda($id);
        
        return $obBorda->tipo;
    }

    /**
     * Método responsável por pegar as pizzas
     * @param integer $id
     * @return Pizza
     */
    private static function getPizza($id){
        // OBTÉM UMA PIZZA PELO ID 
        $obPizza = Pizza::getPizzaById($id);

        return $obPizza;
    }

    /**
     * Método responsável por buscar o tipo de massa
     * @param integer $id 
     * @return string
     */
    private static function getMassa($id){
        // OBTÉM UMA MASSA PELO ID
        $obMassa = Massas::getMassaById($id);

        return $obMassa->tipo;
    }

    /**
     * Método responsável por retornar pizzas sabor
     * @param integer $id
     */
    private static function getPizzaSabor($id){
        // OBTÉM UM A TABELA DE RELACIONAMENTO
        $obPizzaSabor = PizzaSabor::getPizzaSaborById($id);
        $sabores = '';
        foreach($obPizzaSabor as $sabor){
            // OBTÉM O NOME DOS SABORES
            $obSabores = Sabores::getSaborById($sabor['sabores_id']);
            
            $sabores .= View::render('admin/modules/pedidos/sabores',[
                'sabores'=>$obSabores->nome
            ]);
        }
       return $sabores;
    }

    /**
     * Método responsável por atualizar o estatus de produção
     */
    private static function setStatus($status_id){
        // OBTÉM TODOS OS STATUS
        $obStatus = Status::getStatus();
        $status = '';
        while($result = $obStatus->fetchObject(Status::class)){
            $status .= View::render('admin/modules/pedidos/status',[
                'status'=>$result->tipo,
                'id'=>$result->id,  
                'item'=> $status_id == $result->id ? 'selected' : ''
            ]);
        }

        return $status;
    }

    /**
     * Método responsável por buscar deletar um pedido
     * @param Request $request 
     */
    public static function setMixPedido($request){
        // VARIÁVEIS DO POST
        $postVars = $request->getPostVars();
        $obPedido = new EntityPedido;

        $type = $postVars['type'];

        $obPedido->pizzas_id = $postVars['id'];
        $obPedido->status_id = $postVars['status'];

        $type == 'deleted' ? $obPedido->excluir() :$obPedido->atualizar();

        $request->getRouter()->redirect('/admin/pedidos?status='.$type);
    }

    /**
     * Método responsável pelas mensagens
     * @return string
     */
    public static function getStatus($request){
        // QUERY PARAMS
        $queryParams = $request->getQueryParams();
        
        // STATUS
        if(!isset($queryParams['status'])) return '';

        switch($queryParams['status']){
            case 'deleted':
                return Alert::getSuccess('Pedido excluído com sucesso!');
                break;
            case 'updated':
                return Alert::getSuccess('Status atualizado com sucesso!');
                break;
        }
    }
}