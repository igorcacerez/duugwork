<?php
/**
 * ======================================================
 * ======================================================
 * ======================================================
 *
 *  Este arquivo é responsavel por todas as configurações de
 *  rotas do sistema.
 *
 *  ---------------------------------------------------------
 *
 *  - Como criar uma rota?
 *
 *  Todas as rotas são configuradas dentro do array "rotas"
 *
 *
 *  Exemplo:
 *
 *
 *  $rotas [
 *      "PRIMEIRO ITEM DA URL " => [
 *          "controller" => "CONTROLLER QUE DEVE SER CHAMADO",
 *          "metodos" => [
 *              "SEGUNDO ITEM DA URL" => [
 *                  "metodo" => "METODO DO CONTROLLER QUE DEVE SER CHAMADO",
 *                  "parametros" => "QUANTIDADE ITENS QUE DEVE PASSAR POR PARAMETROS"
 *              ],
 *          ],
 *      ]
 * ];
 *
 *
 *  ---------------------------------------------------------
 *
 *  Para configurar a rota da página incial, usa-se no array de rotas
 *  o item default
 *
 *
 *  EXEMPLO:
 *
 *  "default" => [
 *      "controller" => "CONTROLLER DA PAG INICIAL",
 *      "index" => "MÉTODO A SER CHAMADO"
 *  ],
 *
 *  ======================================================
 *  ======================================================
 *  ======================================================
 *
 *  Autor: Igor Cacerez
 *  Data: 17/04/2019
 *
 */


 $rotas = [

     // Configure a página Inicial
     "default" => [
         "controller" => "Principal",
         "index" => "index"
     ],


    /**
     *  ------------------------------------------------
     *  EXEMPLO DE CONFIGURAÇÃO de ROTAS PERSONALIZADAS
     *  ------------------------------------------------
     *
     *  Abaixo você tem alguns exemplos de personalização de rotas,
     *  com os parametros que podem ser ou não passados.
     *
     */

    
    // Todos os Cursos
    "cursos" => [
        "controller" => "Curso",
        "index" => "cursos",
        "parametros" => 1,
        "erro404SemParametro" => false
    ],


    // Cruso especifico
    "curso" => [
        "controller" => "Curso",
        "index" => "especifico",
        "parametros" => 1,
        "erro404SemParametro" => true
    ],


    // Polos no Brasil
    "polos-no-brasil" => [
        "controller" => "Principal",
        "index" => "polos",
        "parametros" => 0
    ],


    // HOME
    "home" => [
        "controller" => "Principal",
        "index" => "home",
        "parametros" => 0
    ],


    // Rotas da categoria ALUNO
    "aluno" => [
        "controller" => "Principal",

        "metodos" => [
            "2-via-boleto" => [
                "metodo" => "segundaVia",
                "parametros" => 0
            ],

            "contrato" => [
                "metodo" => "contrato",
                "parametros" => 0
            ],
        ],
    ],


    // Rotas da categoria Instituicao
    "instituicao" => [
        "controller" => "Principal",

        "metodos" => [
            "contato" => [
                "metodo" => "contato",
                "parametros" => 0
            ],

            "contrato" => [
                "metodo" => "contrato",
                "parametros" => 0
            ],

            "duvidas-frequentes" => [
                "metodo" => "faq",
                "parametros" => 0
            ],

            "quem-somos" => [
                "metodo" => "quemSomos",
                "parametros" => 0
            ],
        ],
    ],


 ];