<?php

namespace App\Model\Entity;

use App\Database\Database;

class Massas
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
     * Method responsible for to get all paste
     */
    public static function getMassas($where = null, $order = null, $limit = null, $field = '*'){
        return (new Database('massas'))->select($where, $order, $limit, $field);
    }

    /**
     * Método responsável por retornar uma massa 
     */
    public static function getMassaById($id){
        return (new Database('massas'))->select('id='.$id)->fetchObject(self::class);
    }
}