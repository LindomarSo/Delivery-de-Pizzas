<?php

namespace App\Database;

class Pagination 
{
    /**
     * Total de resultados
     * @var integer
     */
    private $results;

    /**
     * Limite de itens por página
     * @var integer
     */
    private $limit;

    /**
     * Páginas 
     * @var integer 
     */
    private $page;

    /**
     * Página atual
     * @var integer
     */
    private $currentPage;

    /**
     * Método responsável por iniciar a class
     */
    public function __construct($resuslts, $currentPage, $limit = 10){
        $this->results = $resuslts;
        $this->limit = $limit;
        $this->currentPage = (is_numeric($currentPage) and $currentPage > 0) ? $currentPage : 1;
        $this->calcualte();
    }

    /**
     * Método responsável por calcular as páginas
     */
    private function calcualte(){
        $this->page =$this->results > 0 ? ceil($this->results / $this->limit) : 1;

        $this->currentPage = ($this->currentPage <= $this->page) ? $this->currentPage : $this->page;
    }

    /**
     * Método responsável pela cláusula limit
     * @return string
     */
    public function getLimit(){
        $offset = ($this->limit * ($this->currentPage - 1));

        return $offset.','.$this->limit;
    }

    /**
     * Método responsável por pegar uma página
     */
    public function getPage(){
        // NÃO RETORNA PÁGINAS
        if($this->page == 1)return [];

        $paginas = [];

        for($i = 1; $i <= $this->page; $i++){
            $paginas[] = [
                'paginas'=>$i,
                'atual'=> $i == $this->currentPage
            ];
        }

        return $paginas;
    }
}