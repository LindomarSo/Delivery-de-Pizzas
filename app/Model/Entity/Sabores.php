<?php

namespace App\Model\Entity;

use App\Database\Database;

class Sabores
{
    /**
     * Table ID
     * @var integer
     */
    public $id;

    /**
     * Kind name
     * @var string
     */
    public $tipo;

    /**
     * Method responsible for to get all flavors
     * @param string $where
     * @param string $order 
     * @param integer $limit
     * @param string $field
     */
    public static function getFlavor($where = null, $order = null, $limit = null, $field = '*'){
        return (new Database('sabores'))->select($where, $order, $limit, $field);
    }

    /**
     * Método responsável por buscar sabores pelo id
     * @param integer $id 
     * @return Sabores
     */
    public static function getSaborById($id){
        return (new Database('sabores'))->select('id='.$id)
                                        ->fetchObject(self::class);
    }
}