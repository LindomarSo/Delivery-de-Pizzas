<?php

namespace App\Controller\Pages;
use \App\Utils\View;
use \App\Model\Entity\Organization;

class Page 
{
    /**
     * Método responsável por retornar o header da página 
     * @return string
     */
    public static function getHeader(){
        // INSTÂNCIA DE ORGANIZTION
        $obOrganization = new Organization;
        
        return View::render('pages/header', [
            'path'=>URL
        ]);
    }

    /**
     * Método responsável por retornar o footer
     * @return string
     */
    public static function getFooter(){
        return View::render('pages/footer');
    }
    /**
     * Método responsável por pegar uma página
     * @param string $title
     * @param array $content
     */
    public static function getPage($title, $content){
        return View::render('pages/page', [
            'title'=>$title,
            'path'=>URL,
            'header'=>self::getHeader(),
            'content'=>$content,
            'footer'=>self::getFooter()
        ]);
    }
}