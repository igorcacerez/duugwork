<?php
/**
 * Created by PhpStorm.
 * User: Igor
 * Date: 26/03/2019
 * Time: 17:31
 */

namespace Sistema;


class Controller
{

    public function view($view = null, $dados = null)
    {
        // Verifica se o parametro dado é != de nulo
        if($dados != null)
        {
            extract($dados,EXTR_OVERWRITE);
        }

        //Exibe a View
        include("./app/views/" . $view . ".php");
    }


    public function configCategoria($cat = null)
    {
        switch ($cat) 
        {
            case 'direito-e-seguranca':
                $var = "Direito e Segurança";
                break;

            case 'educacao':
                $var = "Educação";
                break;

            case 'direito-e-seguranca':
                $var = "Direito e Segurança";
                break;

            case 'engenharia':
                $var = "Engenharia";
                break;

            case 'saude':
                $var = "Saúde";
                break;

            case 'design':
                $var = "Design";
                break;

            case 'negocios':
                $var = "Negócios";
                break;

            case 'tecnologia':
                $var = "Tecnologia";
                break;

            case 'turismo-hospitalidade':
                $var = "Turismo e Hospitalidade";
                break;
        }

        return $var;
    }

}