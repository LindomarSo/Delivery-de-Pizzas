<?php

namespace App\Model\Entity;

use App\Database\Database;

class User 
{
    /**
     * ID do usuário
     * @var integer
     */
    public $id;

    /**
     * Nome do usuário 
     * @var string
     */
    public $nome;

    /**
     * Email do usuário 
     * @var string
     */
    public $email;

    /**
     * Senha do usuário 
     * @var string
     */
    public $senha;

    /**
     * Método responsável por pegar um usuário pelo email
     * @param string $email
     * @return User
     */
    public static function getUserByEmail($email){
        return (new Database('usuarios'))->select('email="'.$email.'"')
                                         ->fetchObject(self::class);
    }
}