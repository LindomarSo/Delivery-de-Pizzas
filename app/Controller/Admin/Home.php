<?php

namespace App\Controller\Admin;

use App\Utils\View;

class Home extends Page
{
    /**
     * Método responsável por renderizar a view de Home do painel
     * @return string
     */
    public static function getHome(){
        $content = View::render('admin/modules/home/home',[]);

        return parent::getPanel('Home', $content, 'home');
    }
}