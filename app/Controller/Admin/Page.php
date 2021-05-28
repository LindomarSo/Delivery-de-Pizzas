<?php

namespace App\Controller\Admin;

use App\Utils\View;

Class Page 
{
    /**
     *  Módulos disponíveis no Painel
     * @var array
     */
    private static $modules = [
        'home'=>[
            'label'=>'Home',
            'link'=>URL.'/admin'
        ],
        'pedidos'=>[
            'label'=>'Pedidos',
            'link'=>URL.'/admin/pedidos'
        ]
    ];

    /**
     * Método responsável por renderizar a view da página
     * @param string $title
     * @param string $content
     */
    public static function getPage($title, $content){
        return View::render('admin/page',[
            'title'=>$title,
            'content'=>$content,
            'URL'=>URL
        ]);
    }

    /**
     * Método responsável por renderizar o conteúdo completo 
     * @param 
     */
    public static function getPanel($title, $content, $currentPage){
        $contentPanel = View::render('admin/panel',[
            'menu'=>self::getMenu($currentPage),
            'content'=>$content
        ]);

        return self::getPage($title, $contentPanel);
    }

    /**
     * Método responsável por renderizar os itens do menu
     * @param string $currentPage
     * @return string
     */
    private static function getMenu($currentPage){
        $page = '';
        // RENDIRIZA ITENS
        foreach(self::$modules as $hash => $module){
           $page .= View::render('admin/menu/item',[
               'link'=>$module['link'],
               'page'=>$module['label'],
               'tipo'=>($currentPage == $hash) ? 'text-warning' : 'text-light'
           ]);
        }
      // RETORNA O CONTEÚDO RENDERIZADO
        return View::render('admin/menu/menu',[
            'itens'=>$page
        ]);
    }
}