<?php
/**
 * ----------------------------
 * Created by PhpStorm.
 * User: Igor
 * ----------------------------
 *
 * Classe responsável por criar todos os helpers necessários
 * para o bom funcionamento da api.
 *
 */

namespace Sistema\Helper;


class Api
{

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
     * Método responsável por montar o padrão retorno
     * -----------------------------------------------
     * @param $dados array|null
     */
    public function view($dados = null)
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

        // exibe
        echo json_encode($dados);

        // Mata o processamento
        exit;

    } // END >> Fun::retorno()


} // End >> Class:Api()