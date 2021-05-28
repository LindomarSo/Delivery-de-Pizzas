<?php

namespace App\Model\Entity;

use \App\Database\Database;

class Bordas
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
     * Method responsible for to get all edges
     */
    public static function getBordas($where = null, $order = null, $limit = null, $field = '*'){
        return (new Database('bordas'))->select($where, $order, $limit, $field);
    }
}