<?php

namespace App\Model\Entity;

use \App\Database\Database;

class Pizza
{
    /**
     * Variable reponsible for id_border
     * @var integer
     */
    public $bordas_id;

    /**
     * Variable responsible for id_pasta
     * @var integer
     */
    public $massas_id;

    /**
     * Method responsible for cadastrar one pizza
     * @param Request
     */
    public function cadastrarPizza($request){
        $sql = new Database('pizzas');
       
        $obPizzaSabor = new PizzaSabor;

        $obPizzaSabor->pizzas_id  = $sql->insert([
            'bordas_id'=>$this->bordas_id,
            'massas_id'=>$this->massas_id
        ]);       

        $obPizzaSabor->insertSabor($request);
        
        return true;
    }

    /**
     * Método responsável por resgatar as pizzas do banco
     */
    public static function getPizzaById($id){
        return (new Database('pizzas'))->select('id='.$id)->fetchObject(self::class);
    }
    
}