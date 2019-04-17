<?php
/**
 * ============================================================
 *
 *  Class de configuração do framework, esta classe só deve ser
 *  alterada em ultimo caso.
 *
 * ============================================================
 *
 * Autor: Igor Cacerez
 * Date: 26/03/2019
 * Time: 17:31
 *
 */

namespace Sistema;


class Controller
{
    // Método construtor
    function __construct()
    {
        // Start Session
        if(OPEN_SESSION == true)
        {
            session_start();
        }
    }

    // Método para exibição de páginas VIEW
    public function view($view = null, $dados = null)
    {
        // Verifica se o parametro dado é != de nulo
        if($dados != null)
        {
            extract($dados,EXTR_OVERWRITE);
        }

        //Exibe a View
        include("./app/views/" . $view . ".php");

    } // END >> Fun::view()




    /**
     * ------------------------------------------------------
     *
     * Métodos que facilitam o desenvolvimento de sistemas.
     * Diversos métodos personalizados de rotinas básicas
     * que praticamente todo siststema utiliza e necessita.
     *
     * ------------------------------------------------------
     *
     * Esses métodos são editaveis e não influencia diretamente no
     * funcionamento do famework
     *
     * -------------------------------------------------------
     */


    // Método responsável por realizar upload de arquivos
    public function uploadFile($arquivo = null, $caminho = null, $nome = null)
    {
        // Verifica se o nome foi adicionado
        if($nome == null)
        {
            $nome = date("Y-m-d-his");
        }

        // Pega a extensão
        $ext = explode(".",basename($arquivo['name']));
        $ext = end($ext);

        // Seta o nome do arquivo
        $nome .= "." . $ext;

        // Caminho
        $caminho .= "/" . $nome;

        if(move_uploaded_file($arquivo['tmp_name'], $caminho))
        {
            return $nome;
        }
        else
        {
            return false;
        }

    } // END >> Fun::uploadFile()





} // END >> Class::Controller