<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Model\Entity\Bordas;
use \App\Model\Entity\Massas;
use App\Model\Entity\Sabores;
use App\Model\Entity\Pizza;
use App\Model\Entity\PizzaSabor;

class Home extends Page
{
    /**
     * Método responsável por renderizar a view de Home
     * @return string
     */
    public static function getHome($mensagem = null, $field = null){

        //STATUS
        $status = !is_null($mensagem) ?  View::render('pages/alerts/sucesso',[
            'status'=>$mensagem,
            'field'=>$field
        ]) : '';
        
        $contentView = View::render('pages/home',[
            'tipo'=>self::getBorda(),
            'massa'=>self::getMassa(),
            'sabores'=>self::getSabores(),
            'URL'=>URL,
            'status'=>$status
        ]);

        return parent::getPage('Home', $contentView);
    }

    /**
     * Método responsável por retornar as bordas
     */
    private static function getBorda(){
          // RECEBE BORDAS
          $obBordas = Bordas::getBordas();
        
          $allBorder = '';

          while($obBorda = $obBordas->fetchObject(Bordas::class)){
            $allBorder .= View::render('pages/options/borda',[
                'tipo'=>$obBorda->tipo,
                'id'=>$obBorda->id
            ]);
          }

        return $allBorder;
    }

    /**
     * Método responsável por receber as massas
     */
    private static function getMassa(){
        // RECEBE OS VALORES
        $obMassa = Massas::getMassas();

        $obMassas = '';

        while($obMass = $obMassa->fetchObject(Massas::class)){
            $obMassas .= View::render('pages/options/massa',[
                'tipo'=>$obMass->tipo,
                'id'=>$obMass->id
            ]);
        }

        return $obMassas;
    }

    private static function getSabores(){
        // INSTÂNCIA DATABASE
        $obFlavor = Sabores::getFlavor();

        $obSabor = '';

        while($obSabores = $obFlavor->fetchObject(Sabores::class)){
            $obSabor .= View::render('pages/options/sabores',[
                'sabores'=>$obSabores->nome,
                'id'=>$obSabores->id
            ]);
        }

        return $obSabor;
    }

     /**
     * Método responsável por cadastrar um pedido
     * @param Request 
     * @return string
     */
    public static function insertPedio($request){
        // DADOS DO POST
        $postVars = $request->getPostVars();
        // INSTÂNCIA DE PIZZAS
        $obPizza = new Pizza;
        //INSTÂNCIA DE SABORES
        $obSabores = new PizzaSabor;
        if($postVars == []){
            return self::getHome('Não são permitidos campos vazios!', 'alert-danger');
        }else{
            if(count($postVars) <= 2){
                return self::getHome('Não são permitidos campos vazios', 'alert-danger');
            }else if(count($postVars['sabores']) > 3){
                return self::getHome('São permitidos apenas três sabores!', 'alert-danger');
            }else{
                $obPizza->bordas_id = $postVars['borda'];
                $obPizza->massas_id = $postVars['massa'];
                $obSabores->sabores_id = $postVars['sabores'];
            }
        }
           
        return $obPizza->cadastrarPizza($request) ? 
                self::getHome('Pedido feito com sucesso!', 'alert-success') :
                self::getHome('Erro ao processar o pedido!', 'alert-danger');
    }
}