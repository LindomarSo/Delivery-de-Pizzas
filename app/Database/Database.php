<?php

namespace App\Database;

use \PDO;
use \PDOException;

class Database 
{
    /**
     * Varaible responsible for to receive the HOST
     * @var string
     */
    public static $host;

     /**
     * Variable responsible for to receive database name
     * @var string
     */
    public static $dbname;

    /**
     * Variable responsible for to receive the USER
     * @var string
     */
    public  static $user;

    /**
     * Variable responsible for to receive the PASS
     * @var string
     */
    public static $pass;

    /**
     * Variable responsibel for to receive the table name
     * @var string
     */
    public $table;

    /**
     * Variable responsible for to connect with database
     */
    public $connection;

    /**
     * Construct of the class
     */
    public function __construct($table = ''){
        $this->table = $table;
        $this->connect();
    }

    /**
     * Method responsible for receive the configurations
     * @param string $host
     * @param string $dbname
     * @param string $user
     * @param string $pass
     */
    public static function config($host,$dbname,$user,$pass){
        self::$host = $host;
        self::$dbname = $dbname;
        self::$user = $user;
        self::$pass = $pass;
    }

    private function connect(){
        try{
            $this->connection = new PDO("mysql:host=".self::$host."; dbname=".self::$dbname,"root",self::$pass);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $e){
            die('Erro ao conectar ao banco '. $e->getMessage());
        }
    }

    /**
     * Method responsible for execute one query
     * @param string $query
     */
    private function execute($query, $params = []){
        try{
            $statement = $this->connection->prepare($query);
            $statement->execute($params);

            return $statement;
        }catch(PDOException $e){
            die("Erro ao executar a query ".$e->getMessage());
        }
    }

    /**
     * Method responsible for to read all registers
     * @param string $where
     * @param string $order
     * @param string $limit
     * @return PDOStatement
     */
    public function select($where = null, $order = null, $limit = null, $field = '*'){
        $where = strlen($where) ? "WHERE ".$where : "";
        $order = strlen($order) ? " ORDER BY ".$order : "";
        $limit = strlen($limit) ? "LIMIT ".$limit : "";
        $query = "SELECT ".$field." FROM ".$this->table." ".$where. " ".$order. " ".$limit;
  
        return $this->execute($query);
    }

    /**
     * Method responsible for to insert a data 
     */
    public function insert($params = []){
        $fields = array_keys($params);
        $binds = array_pad([], count($fields), '?');
        
        $query = "INSERT INTO ".$this->table." (".implode(', ',$fields).") VALUES(".implode(',', $binds).")";

        $this->execute($query, array_values($params));

        return $this->connection->lastInsertId();
    }

    /**
     * Método responsável por deletar um registro no banco 
     * @param string $where
     * @return boolean
     */
    public function delete($where){
        // MONTA A QUERY
        $query = "DELETE FROM ".$this->table." WHERE ".$where;
        
        $this->execute($query);

        return true;
    }

    /**
     * Método responsável por atualizar um registro no banco
     * @param integer $where
     * @return boolean
     */
    public function update($where, $params = []){
        $field = array_keys($params);
        // MONTA A QUERY
        $query  = "UPDATE ".$this->table." SET ".implode('=?, ',$field)."=? WHERE ".$where;
        
        $this->execute($query, array_values($params));

        return true;
    }
}