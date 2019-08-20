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

$Rotas->onError("404", function (){
   echo "Erro - 404";
});


$Rotas->group("Principal","api","Principal");


$Rotas->onGroup("Principal","GET","a","index");

$Rotas->on("GET","","Principal::index");