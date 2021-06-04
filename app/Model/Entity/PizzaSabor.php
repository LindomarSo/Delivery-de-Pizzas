<?php

namespace App\Model\Entity;

use App\Database\Database;

class PizzaSabor extends Pedido
{
     /**
     * Variable for sabores_id
     * @var 
     */
    public $sabores_id;

    /**
     * Método responsável por um inserir um sabor no banco
     * @param Request $request
     */
    public function insertSabor($request){
        // CONEXÃO COM O BANCO
        $connection = new Database('pizza_sabor');
        // DADOS DA REQUEST
        $postVars = $request->getPostVars();
        $this->sabores_id = $postVars['sabores'];
        // INSERINDO DADOS
        foreach($this->sabores_id as $key){
            $connection->insert([
                'pizzas_id'=>$this->pizzas_id,
                'sabores_id'=>$key
            ]);
        }

        $this->pedido();

        return true;
    }

    /**
     * Método responsável por buscar um registro pelo ID
     * @param integer $id 
     * @return PizzaSabor
     */
    public static function getPizzaSaborById($id){
        return (new Database('pizza_sabor'))->select('pizzas_id='.$id)
                                            ->fetchAll(\PDO::FETCH_ASSOC);
    }
}