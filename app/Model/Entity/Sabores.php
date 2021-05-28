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
     */
    public static function getFlavor($where = null, $order = null, $limit = null, $field = '*'){
        return (new Database('sabores'))->select($where, $order, $limit, $field);
    }
}