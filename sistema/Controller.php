<?php
/**
 * ============================================================
 *
 *  Class de configuração do framework, esta classe só deve ser
 *  alterada em ultimo caso.
 *
 * ============================================================
 *
 * Autor: igorcacerez
 * Date: 26/03/2019
 * 
 */

namespace Sistema;


class Controller
{
    // Variaveis globais
    private $vars = null;


    // Método construtor
    function __construct()
    {
        // Verifica inicialização da session
        $this->sessionInicialize();

    } // End >> Fun::__construct()



    /**
     * Método responsável por dar um debug na tela.
     * ----------------------------------------------
     * @param $item null|array
     * @param $tipo string|null
     */
    public function debug($item = null, $tipo = "array")
    {
        if($tipo == "array")
        {
            echo "<pre>" . $item . "</pre>";
        }
        else
        {
            header("Content-type: application/json; charset=utf-8");
            echo json_encode($item);
        }

        exit;
    } // End >> Fun::debug()



    /**
     * Método responsável por chamar a view correta e exibir os
     * dados necessários da mesma
     * ----------------------------------------------------------
     *
     * @param null $view
     * @param null $dados
     */
    public function view($view = null, $dados = null)
    {
        // Verifica se o parametro dado é != de nulo
        if($dados != null)
        {
            foreach($dados as $var_name => $var_value)
            {
                $this->vars[$var_name] = $var_value;
            }
        }

        // Verifica se possui variaveis
        if($this->vars != null)
        {
            extract($this->vars,EXTR_OVERWRITE);
        }


        //Exibe a View
        include("./app/views/" . $view . ".php");

    } // END >> Fun::view()




    // Método responsável por formatar um numero na casa do milhar, deixando
    // as siglas na frente: K,M,B,T,Q
    public function formatNumero($numero = null)
    {
        // Variaveis
        $cont = 0;
        $array  = ["","K","M","B","T","Q"];

        // Divide o numero por mil
        while ($numero >= 1000)
        {
            $numero = $numero / 1000;
            $cont++;
        }


        // Verifica se o numero não é inteiro
        if(is_int($numero) == false)
        {
            // Deixa com duas casas decimais
            $numero = number_format($numero,2,".");
        }

        // Retorna o numero com a letra
        return $numero . $array[$cont];
    }




    /**
     * ========================================================
     *                   Métodos Privados
     * ========================================================
     */


    /**
     * Método responsável por verifica se deve inicializar
     * a session de um determinado usuário.
     */
    private function sessionInicialize()
    {
        // Verifica se é para add a session
        if(OPEN_SESSION == true)
        {
            // Verifica se a session já está ativa
            if(!isset($_SESSION))
            {
                // Inicia a session
                session_start();
            }
        }

    } // End >> fun::sessionInicialize()


} // END >> Class::Controller