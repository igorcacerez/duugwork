<?php
/**
 * Created by PhpStorm.
 * User: Igor
 * Date: 26/03/2019
 * Time: 18:29
 */

namespace Controller;

use DuugWork\Controller as CI_controller;


class Principal extends CI_controller
{

    // Método construtor
    function __construct()
    {
        // Carrega o contrutor da classe pai
        parent::__construct();
    }


    public function index()
    {
        // Variavel
        $texto = "Hello World";

        // Array de variaveis que deve ser exibida na view
        $dados = ["ola" => $texto];

        // Chama a view
        $this->view("index", $dados);
    }


} // END::Class Principal