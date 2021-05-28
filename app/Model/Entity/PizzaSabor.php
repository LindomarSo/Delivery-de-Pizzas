<?php

namespace App\Model\Entity;

use App\Database\Database;

class PizzaSabor 
{
    /**
     * Vabiable for pizza_id
     * @var integer
     */
    public $pizzas_id;

    /**
     * Variable for sabores_id
     * @var integer
     */
    public $sabores_id;

    /**
     * Variable responsible for status
     * @var integer
     */
    public $status_id = 1;

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

        $this->status();

        return true;
    }

    /**
     * Method responsible for update the status
     */
    public function status(){
        // CONNECTION DATABASE
        $connection =  new Database('pedidos');
        $connection->insert([
            'pizzas_id'=>$this->pizzas_id,
            'status_id'=>$this->status_id
        ]);
    }
}