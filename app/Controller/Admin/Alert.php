<?php

namespace App\Controller\Admin;

use App\Utils\View;

class Alert 
{
    /**
     * Método responsável por carregar a view de status
     * @return string
     */
    public static function getSuccess($message){
        return View::render('alert/alert',[
            'status'=>$message,
            'field'=>'alert-success'
        ]);
    }

    /**
     * Método responsável por carregar a view de status
     * @return string
     */
    public static function getError($message){
        return View::render('admin/alert/alert',[
            'status'=>$message,
            'field'=>'alert-danger'
        ]);
    }
}