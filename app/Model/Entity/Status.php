<?php

namespace App\Model\Entity;

use App\Database\Database;

class Status 
{
    /**
     * ID do status
     * @var integer
     */
    public $id;

    /**
     * Tipo do status
     * @var string
     */
    public $tipo;

    /**
     * Método responsável por buscar os status
     */
    public static function getStatus(){
        return (new Database('status'))->select();
    }
}