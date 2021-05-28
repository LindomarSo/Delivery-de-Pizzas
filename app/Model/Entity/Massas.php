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
}