<?php

namespace App\Utils;

class View 
{
    /**
     * Método responsável por definir uma view
     * @return string
     */
    private static function getContentView($view){
        $file = __DIR__.'/../../resources/view/'.$view.'.html';

        return file_exists($file) ? file_get_contents($file) : '';
    }
    /**
     * Método responsável por renderizar uma view
     */
    public static function render($view, $params = []){
        $content = self::getContentView($view);

        // CHAVES 
        $keys = array_keys($params);
        $keys = array_map(function($item){
            return '{{'.$item.'}}';
        },$keys);
        
        return str_replace($keys,array_values($params),$content);
    }
}