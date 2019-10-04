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

        // Verifica se informou o codigo do erro
        if(!isset($dados["code"]))
        {
            $dados["code"] = "400";
        }

        // exibe
        echo json_encode($dados);

        // Mata o processamento
        exit;

    } // END >> Fun::retorno()


} // End >> Class:Api()