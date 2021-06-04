<?php

namespace App\Model\Entity;

use App\Database\Database;  

class Pedido
{
    /**
     * Vabiable for pizza_id
     * @var integer
     */
    public $pizzas_id;

    /**
     * Variable responsible for status
     * @var integer
     */
    public $status_id = 1;

    /**
     * Método responsável por inserir um pedido no banco
     * @return PDOStatement
     */
    public function pedido(){
        // CONNECTION DATABASE
        $connection =  new Database('pedidos');
        $connection->insert([
            'pizzas_id'=>$this->pizzas_id,
            'status_id'=>$this->status_id
        ]);
    }

    /**
     * Método responsável por resgatar um pedido pelo id
     * @return Pedido
     */
    public function getPedidoById(){
        return (new Database('pedidos'))->select('pizzas_id='.$this->pizzas_id)->fetchObject(self::class);
    }

    /**
     * Método responsável por resgatar os pedidos
     * @return PDOStatement
     */
    public static function getAsks($where = null, $order = null, $limit = null, $field = '*'){
        return (new Database('pedidos'))->select($where, $order, $limit, $field);
    }

    /**
     * Método responsável por excluir um pedido
     * @return boolean
     */
    public function excluir(){
        return (new Database('pedidos'))->delete('pizzas_id='.$this->pizzas_id);
    }

    /**
     * Método responsável por atualizar um dado no banco 
     * @return boolean
     */
    public function atualizar(){
        return (new Database('pedidos'))->update('pizzas_id='.$this->pizzas_id,[
            'pizzas_id'=>$this->pizzas_id,
            'status_id'=>$this->status_id
        ]);
    }
}