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
            echo "<pre>";
            echo print_r($item);
            echo "</pre>";
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

        // Array de plugins padrão
        $this->vars["pluginsAutoLoad"] = unserialize(PLGUINS_AUTOLOAD);

        // Verifica se possui variaveis
        if($this->vars != null)
        {
            extract($this->vars,EXTR_OVERWRITE);
        }


        //Exibe a View
        include("./app/views/" . $view . ".php");

    } // END >> Fun::view()


    /**
     * Método responsável por montar o padrão retorno
     * -----------------------------------------------
     * @param $dados array|null
     */
    public function api($dados = null)
    {
        header("Content-type: application/json; charset=utf-8");

        // Verifica se não informou o erro
        if(!isset($dados["tipo"]))
        {
            $dados["tipo"] = false;
        }

        // Verifica se informou a mensagem
        if(!isset($dados["mensagem"]))
        {
            $dados["mensagem"] = null;
        }


        // Verifica se não informou o data
        if(!isset($dados["objeto"]))
        {
            $dados["objeto"] = null;
        }

        // Verifica se informou o codigo do erro
        if(!isset($dados["code"]))
        {
            $dados["code"] = "400";
        }

        // exibe
        $dados = json_encode($dados);

        // Verifica se converteu
        if($dados != false)
        {
            echo $dados;
        }
        else
        {
            echo json_last_error_msg();
        }

        // Mata o processamento
        exit;

    } // END >> Fun::api()


    /**
     * Método responsável por configurar um array contendo as chaves de seo e smo
     * ---------------------------------------------------------------------------
     * @param null|array $seo
     * @param null|array $smo
     * ---------------------------------------------------------------------------
     * @return array
     */
    public function getSEO($seo = null, $smo = null)
    {
        // Monta o array padrão
        $dados = [
            "seo" => [
                "title" => SITE_NOME . " Socks | Conforto e versatilidade aos seus pés",
                "description" => "Meônia Socks possui as meias mais confortaveis e versateis, produzida para agradar até os mais exigentes.",
                "keywords" => "meônia, socks, meias, conforto, confortavel, birigui, fábrica, loja da fábrica, versatil",
                "distribution" => "global",
                "revisit-after" => "2 Days",
                "robots" => "ALL",
                "language" => "pt-br"
            ],
            "smo" => [
                "url" => BASE_URL,
                "title" =>  SITE_NOME . " | Nada é básico com Meônia Socks",
                "site_name" => SITE_NOME,
                "description" => "Meônia Socks possui as meias mais confortaveis e versateis, produzida para agradar até os mais exigentes.",
                "image" => BASE_URL . 'assets/custom/img/smo.jpg',
                "image_type" => "image/png",
                "image_width" => "960",
                "image_height" => "960"
            ]
        ];

        // Verifica se o array de seo é diferente de null
        if($seo != null)
        {
            // Percorre o array
            foreach ($seo as $item => $value)
            {
                // Realiza a modificação no dados
                $dados["seo"][$item] = $value;
            }
        }

        // Verifica se o array de smo é diferente de null
        if($smo != null)
        {
            // Percorre o array
            foreach ($smo as $item => $value)
            {
                // Realiza a modificação no dados
                $dados["smo"][$item] = $value;
            }
        }

        // Retorna o array pre-configurado
        return $dados;

    } // End >> fun::getSEO()



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